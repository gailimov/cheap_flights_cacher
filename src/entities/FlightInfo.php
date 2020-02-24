<?php

namespace app\entities;

class FlightInfo
{
    private bool $isInvalid;
    private bool $isPriceChanged;
    private int $total;

    public function __construct(array $flight)
    {
        $this->isInvalid = $flight['flights_invalid'];
        $this->isPriceChanged = $flight['price_change'];
        $this->total = $flight['total'];
    }

    public function isInvalid(): bool
    {
        return $this->isInvalid;
    }

    public function isPriceChanged(): bool
    {
        return $this->isPriceChanged;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
