<?php

namespace App\Presenters;

use App\Models\SearchRequest;
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
            'params' => $this->resource->params,
        ];
    }
}
