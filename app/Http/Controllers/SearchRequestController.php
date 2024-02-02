<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchRequest;
use Illuminate\Support\Facades\URL;
use App\Presenters\FreeTimeSlotPresenter;
use App\Repositories\MyWellnessRepository;
use App\Presenters\SearchRequestPresenter;

class SearchRequestController extends Controller
{


    public function show(Request $request, int $id)
    {
        $searchRequest = SearchRequest::with('free_time_slots')->findOrfail($id);


        $myWellnessRepository = new MyWellnessRepository();





        return inertia('SearchRequestDetail', [
            'showSuccessMessage' => (bool) $request->session()->get('success'),
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



        session()->put('start-form', [
            'email' => $request->email,
        ]);


        return redirect()->route('start', ['step' => 2]);


    }



    private function stepTwo(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $filters = $myWellnessRepository->getAvailableFilters();

        $validationRules = [];


        foreach ($filters as $filter) {
            if(optional($filter)['is_required']) {
                $validationRules['filters.'. $filter['name']] = ['required'];
            }
        }


        $request->validate($validationRules);


        $searchRequest = SearchRequest::create([
            'params' => $request->filters,
            'email' => $request->email
        ]);


        return redirect()
            ->signedRoute('search-requests.show', $searchRequest->id)
            ->with('success', true);

    }

}
