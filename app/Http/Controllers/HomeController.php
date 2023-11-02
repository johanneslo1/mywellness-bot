<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SearchRequest;
use Illuminate\Support\Facades\Http;
use App\Repositories\MyWellnessRepository;
use App\Presenters\SearchRequestPresenter;

class HomeController extends Controller
{
    public function index(MyWellnessRepository $myWellnessRepository)
    {
        $filters = $myWellnessRepository->getAvailableFilters();


        return Inertia::render('Home', [
            'availableFilters' => $filters,
            'searchRequests' => SearchRequestPresenter::collection(SearchRequest::all()),
        ]);
    }


}
