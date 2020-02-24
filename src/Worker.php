<?php

namespace app;

use app\entities\Flight;

class Worker
{
    private Service $service;
    private Repository $repository;
    private array $routes;

    public function __construct(Service $service, Repository $repository, array $routes)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->routes = $routes;
    }

    public function prepare()
    {
        foreach ($this->routes as $route) {
            foreach (Utils::getDatesForMonth() as $date) {
                $flights = $this->service->getCheapestByDate($date, $route['from'], $route['to']);

                if (!$flights) {
                    continue;
                }

                foreach ($flights as $index => $flight) {
                    $flightInfo = $this->service->check($flight->getBookingToken());

                    if (!$flightInfo) {
                        continue;
                    }

                    if ($flightInfo->isInvalid()) {
                        unset($flights[$index]);
                    } elseif ($flightInfo->isPriceChanged()) {
                        $flight->updatePrice($flightInfo->getTotal());
                    }
                }

                foreach ($flights as $flight) {
                    if ($this->repository->getById($flight->getId())) {
                        if ($flight->isPriceChanged()) {
                            $this->repository->updatePrice($flight);
                        }
                    } else {
                        $this->repository->insert($flight);
                    }
                }
            }
        }
    }

    public function check()
    {
        $flights = $this->repository->getAllUpcoming();

        foreach ($flights as $flight) {
            $flight = new Flight($flight);
            $flightInfo = $this->service->check($flight->getBookingToken());

            if (!$flightInfo) {
                continue;
            }

            if ($flightInfo->isInvalid()) {
                $this->repository->delete($flight);
            } elseif ($flightInfo->isPriceChanged()) {
                $flight->updatePrice($flightInfo->getTotal());
                $this->repository->updatePrice($flight);
            }
        }
    }
}
