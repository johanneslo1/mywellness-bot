<?php

namespace App\Http\Controllers;

use App\Models\SearchRequest;
use App\Presenters\SearchRequestPresenter;
use App\Repositories\MyWellnessRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function showConfigurator(Request $request, MyWellnessRepository $myWellnessRepository)
    {
        $filters = $myWellnessRepository->getAvailableFilters();

        $step = intval($request->step) ?: 1;

        return Inertia::render('Configurator', [
            'availableFilters' => $filters,
            'step'             => $step,
            'hCaptchaSiteKey'  => config('services.hcaptcha.sitekey'),
            'searchRequests'   => SearchRequestPresenter::collection(SearchRequest::all()),
        ]);
    }
}
