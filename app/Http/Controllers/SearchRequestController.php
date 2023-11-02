<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchRequest;
use App\Repositories\MyWellnessRepository;

class SearchRequestController extends Controller
{
    public function store(Request $request, MyWellnessRepository $myWellnessRepository) {


        $res = $myWellnessRepository->getAvailabilityDays(today(), $request->all());

        if($res->successful()) {
            $searchRequest = SearchRequest::create([
                'params' => $request->all(),
                'email' => 'technikclou@gmail.com',
            ]);
        }


        return back()->with('success', 'Erfolgreich! Der Suchauftrag wurde gespeichert.');
    }


    public function toggleSearchRequest(Request $request, int $id) {

        $request->validate([
            'acitve' => ['required', 'boolean'],
        ]);

        $searchRequest = SearchRequest::find($id);

        $searchRequest->update([
            'active' => $request->active,
        ]);

        return redirect()->back()->with('success', 'Erfolgreich!');
    }

}
