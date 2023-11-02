<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\SearchRequest;
use Illuminate\Support\Facades\Http;

class MyWellnessRepository
{
    public function getAvailableFilters()
    {
        $url = 'https://buchen.mywellness.de/api/customer-app/v1/suite-availability-filters?with_suite_type=null';

        return Http::get($url)->json();
    }



    public function getAvailabilityDays(Carbon $month, array $params): \Illuminate\Http\Client\Response
    {
        $res = Http::get('https://buchen.mywellness.de/api/customer-app/v1/suite-availability-days', array_merge($params, [
            'month' => $month->format('Y-m'),
        ]));


        return $res;
    }



    public function getFullyAvailableDaysOfNextThreeMonths(SearchRequest $searchRequest)
    {
        $months = collect([today(),  today()->addMonth()/*, today()->addMonths(2) */]);

        return $months->flatMap(function (Carbon $month) use ($searchRequest) {
            $json = $this->getAvailabilityDays($month, $searchRequest->params)
                ->json();

            return $json['availability_days'];
        })
            ->map(fn($item) => ['date' => Carbon::parse($item['availability_date']), 'status' => $item['availability_status']])
            ->filter(fn($item) => $item['status'] === 1)
            ->filter(fn ($item) => in_array($item['date']->format('Y-m-d'), $searchRequest->params['dates']));

    }



    public function getPackages()
    {
        $url = 'https://buchen.mywellness.de/api/customer-app/v1/price-configuration-periods/current/packages?with[]=empty_package';

        return Http::get($url)->json();
    }



    public function authenticate()
    {

        return Http::post('https://buchen.mywellness.de/api/customer-app/v1/customers/authenticate', [
//            'email' => '',
//            'password' => '',
        ])->json();

    }
}
