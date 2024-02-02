<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchRequest;
use Illuminate\Support\Facades\URL;
use App\Presenters\FreeTimeSlotPresenter;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MyWellnessRepository;
use App\Presenters\SearchRequestPresenter;

class SearchRequestController extends Controller
{


    public function show(Request $request, int $id)
    {
        $searchRequest = SearchRequest::with(['free_time_slots' => fn ($query) => $query->orderBy('date')])->findOrfail($id);


        $myWellnessRepository = new MyWellnessRepository();



        return inertia('SearchRequestDetail', [
            'showSuccessMessage' => (bool) $request->session()->get('success'),
            'availableFilters' => $myWellnessRepository->getAvailableFilters(),
            'searchRequest' => SearchRequestPresenter::make($searchRequest),
            'freeTimeSlots' => FreeTimeSlotPresenter::collection($searchRequest->free_time_slots)
        ]);
    }


    public function start(Request $request, MyWellnessRepository $myWellnessRepository)
    {

        $step = intval($request->step) ?: 1;


        return match ($step) {
            1 => $this->stepOne($request, $myWellnessRepository),

            2 => $this->stepTwo($request, $myWellnessRepository),
            default => abort(404),
        };


    }



    public function toggleSearchRequest(Request $request, int $id)
    {

        $request->validate([
            'acitve' => ['required', 'boolean'],
        ]);

        $searchRequest = SearchRequest::find($id);

        $searchRequest->update([
            'active' => $request->active,
        ]);

        return redirect()->back()->with('success', 'Erfolgreich!');
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
            'prefered_weekdays' => ['required', 'array'],
            'prefered_weekdays.*' => ['required', 'integer', 'min:1', 'max:7'],
        ];


        foreach ($filters as $filter) {
            if(optional($filter)['is_required']) {
                $validationRules['filters.'. $filter['name']] = ['required'];
            }
        }

        $attributeNames = collect($filters)->mapWithKeys(fn($filter) => ['filters.' . $filter['name'] => $filter['title']])->toArray();

        Validator::make($request->all(), $validationRules)
            ->setAttributeNames($attributeNames)
            ->validate();

        $request->validate($validationRules);


        $searchRequest = SearchRequest::create([
            'params' => $request->filters,
            'email' => $request->email
        ]);


        return redirect()
            ->signedRoute('search-requests.show', $searchRequest->id)
            ->with('success', true);

    }

    public function destroyByEmail(Request $request) {

        if(!$request->has('email')) {
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
