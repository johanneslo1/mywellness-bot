<?php

namespace App\Repositories;

use App\Models\SearchRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MyWellnessRepository
{
    public function getAvailableFilters(): array
    {
        $filters = Cache::remember(
            key: 'availableFilters',
            ttl: 60 * 60 * 24,
            callback: fn () => Http::get('https://buchen.mywellness.de/api/customer-app/v1/suite-availability-filters?with_suite_type=null')
                ->json()
        );

        return array_values(array_filter($filters, fn ($filter) => !in_array($filter['name'], ['date', 'notice'])));
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
        $months = collect([today(), today()->addMonth()/*, today()->addMonths(2) */]);

        return $months->flatMap(function (Carbon $month) use ($searchRequest) {
            $res = $this->getAvailabilityDays($month, $searchRequest->params);

            $json = $res->json();

            if ($res->failed()) {
                throw new Exception('Failed to get availability days. Api returned HTTP Code '.$res->status().' with message: '.$res->body());
            }

            return $json['availability_days'];
        })
            ->map(fn ($item) => ['date' => Carbon::parse($item['availability_date']), 'status' => $item['availability_status']])
            ->filter(fn ($item) => $item['status'] === 1)
            ->filter(fn ($item) => !array_key_exists('dates', $searchRequest->params) || in_array($item['date']->format('Y-m-d'), $searchRequest->params['dates']));
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
