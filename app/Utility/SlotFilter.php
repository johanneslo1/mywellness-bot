<?php

namespace App\Utility;

class SlotFilter
{
    public function __construct(protected array $params)
    {
    }

    public function getName(): string
    {
        return $this->params['name'];
    }

    public function getTitle(): string
    {
        return $this->params['title'];
    }

    public function isSelect(): bool
    {
        return $this->params['type'] === 'select';
    }

    public function isRequired(): bool
    {
        return $this->params['is_required'];
    }

    public function allowsMultipleValues(): bool
    {
        return $this->isSelect()
            ? $this->params['allow_multiple_values']
            : false;
    }

    public function maxValuesAllowed(): int
    {
        return $this->isSelect()
            ? $this->params['maximum_values_allowed']
            : 1;
    }
}
