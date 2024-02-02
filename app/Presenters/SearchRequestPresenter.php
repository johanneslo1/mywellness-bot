<?php

namespace App\Presenters;

use App\Models\SearchRequest;
use App\Repositories\MyWellnessRepository;
use AdditionApps\FlexiblePresenter\FlexiblePresenter;

/**
 * @property SearchRequest $resource
 */
class SearchRequestPresenter extends FlexiblePresenter
{
    public function values(): array
    {
        return [
            'id' => $this->resource->id,
            'last_check_at' => $this->resource->last_check_at?->toDateTimeString(),
            'email' => $this->resource->email,
            'active' => $this->resource->active,
            'filters' => function () {

                $myWellness = app(MyWellnessRepository::class);

                $filters = collect($myWellness->getAvailableFilters());


               return collect($this->resource->params)
                   ->map(function ($value, $key) use ($filters) {
                    $filter = $filters->where('name', $key)->first();


                    $option = array_key_exists('options', $filter)
                        ? collect($filter['options'])
                        ->where('value', $value)
                        ->first()
                        : null;


                    return [
                        'title' => optional($filter)['title'],
                        'value' => $option
                            ? $option['title']
                            : $value
                    ];
                })->values();

            },
        ];
    }
}
