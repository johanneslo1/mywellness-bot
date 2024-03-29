<?php

namespace App\Http\Controllers;

use App\Models\SearchRequest;
use App\Presenters\FreeTimeSlotPresenter;
use App\Presenters\SearchRequestPresenter;
use App\Repositories\MyWellnessRepository;
use App\Rules\ValidHCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchRequestController extends Controller
{
    public function show(Request $request, int $id)
    {
        $searchRequest = SearchRequest::with(['free_time_slots' => fn ($query) => $query->orderBy('date')])->findOrfail($id);

        $myWellnessRepository = new MyWellnessRepository();

        return inertia('SearchRequestDetail', [
            'showSuccessMessage' => (bool) $request->session()->get('success'),
            'availableFilters'   => $myWellnessRepository->getAvailableFilters(),
            'searchRequest'      => SearchRequestPresenter::make($searchRequest),
            'freeTimeSlots'      => FreeTimeSlotPresenter::collection($searchRequest->free_time_slots),
        ]);
    }

    public function store(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $step = intval($request->step) ?: 1;

        return match ($step) {
            1 => $this->stepOne($request, $myWellnessRepository),

            2       => $this->stepTwo($request, $myWellnessRepository),
            default => abort(404),
        };
    }

    private function stepOne(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return redirect()->route('start', ['step' => 2]);
    }

    private function stepTwo(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $filters = $myWellnessRepository->getAvailableFilters();

        $validationRules = [
            'prefered_weekdays'   => ['required', 'array'],
            'prefered_weekdays.*' => ['required', 'integer', 'min:1', 'max:7'],
            //            'h_captcha_response' => ['required', new ValidHCaptcha()],
        ];

        foreach ($filters as $filter) {
            if (optional($filter)['is_required']) {
                $validationRules['filters.'.$filter['name']] = ['required'];
            }
        }

        $attributeNames = collect($filters)->mapWithKeys(fn ($filter) => ['filters.'.$filter['name'] => $filter['title']])->toArray();

        Validator::make($request->all(), $validationRules)
            ->setAttributeNames($attributeNames)
            ->validate();

        $request->validate($validationRules);

        $searchRequest = SearchRequest::create([
            'params'             => $request->filters,
            'preferred_weekdays' => $request->prefered_weekdays,
            'email'              => $request->email,
        ]);

        return redirect()
            ->signedRoute('search-requests.show', $searchRequest->id)
            ->with('success', true);
    }

    public function destroyByEmail(Request $request)
    {
        if (!$request->has('email')) {
            return inertia('Unsubscribe', [
                'email' => session()->get('success'),
            ]);
        }

        SearchRequest::where('email', $request->email)->delete();

        return redirect()
            ->route('unsubscribe')
            ->with('success', $request->email);
    }
}
