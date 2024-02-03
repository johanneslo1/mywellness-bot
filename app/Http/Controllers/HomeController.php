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
    public function showConfigurator(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $filters = $myWellnessRepository->getAvailableFilters();

        $step = intval($request->step) ?: 1;


        return Inertia::render('Configurator', [
            'availableFilters' => $filters,
            'step' => $step,
            'hCaptchaSiteKey' => config('services.hcaptcha.sitekey'),
            'searchRequests' => SearchRequestPresenter::collection(SearchRequest::all()),
        ]);
    }


}
