<?php

namespace App\Presenters;

use AdditionApps\FlexiblePresenter\FlexiblePresenter;

class FreeTimeSlotPresenter extends FlexiblePresenter
{
    public function values(): array
    {
        return [
             'id' => $this->resource->id,
             'date' => $this->resource->date,
             'status' => $this->resource->status,
             'url' => $this->resource->url,
        ];
    }
}
