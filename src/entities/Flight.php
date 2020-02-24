<?php

namespace app\entities;

use DateTimeImmutable;

class Flight
{
    private string $id;
    private ?string $from;
    private ?string $to;
    private ?DateTimeImmutable $departureTime;
    private ?DateTimeImmutable $arrivalTime;
    private ?string $price;
    private string $bookingToken;

    private bool $priceChanged = false;

    public function __construct(array $flight)
    {
        $this->id = $flight['id'];
        $this->from = $flight['flyFrom'] ?? null;
        $this->to = $flight['flyTo'] ?? null;
        $this->departureTime = isset($flight['dTime'])
            ? (new DateTimeImmutable())->setTimestamp($flight['dTime'])
            : null;
        $this->arrivalTime = isset($flight['aTime'])
            ? (new DateTimeImmutable())->setTimestamp($flight['aTime'])
            : null;
        $this->price = $flight['price'] ?? null;
        $this->bookingToken = $flight['booking_token'];
    }

    public function updatePrice(int $price): void
    {
        $this->price = $price;
        $this->priceChanged = true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function getDepartureTime(): ?DateTimeImmutable
    {
        return $this->departureTime;
    }

    public function getArrivalTime(): ?DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getBookingToken(): string
    {
        return $this->bookingToken;
    }

    public function isPriceChanged(): bool
    {
        return $this->priceChanged;
    }
}
