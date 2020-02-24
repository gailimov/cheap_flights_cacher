<?php

namespace app;

use app\entities\{Flight, FlightInfo};

class Service
{
    private const FLIGHTS_LIST_ENDPOINT = 'https://api.skypicker.com/flights?';
    private const FLIGHTS_CHECK_ENDPOINT = 'https://booking-api.skypicker.com/api/v0.1/check_flights?';
    private const CHECK_ATTEMPTS = 300;

    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @return Flight[]
     */
    public function getCheapestByDate(string $date, string $from, string $to): array
    {
        echo "Getting cheapest flights from $from to $to on $date...\n";

        $params = [
            'partner' => 'picky',
            'fly_from' => $from,
            'fly_to' => $to,
            'date_from' => $date,
            'date_to' => $date
        ];
        $uri = self::FLIGHTS_LIST_ENDPOINT . http_build_query($params);
        $response = $this->apiClient->fetch($uri);

        if (!$response) {
            return [];
        }

        $flights = $response['data'];

        if (!$flights) {
            echo "No flights found\n";
            return [];
        }

        $minPrice = min(array_column($flights, 'price'));
        $flights = array_filter($flights, function ($flight) use ($minPrice) {
            return $flight['price'] == $minPrice;
        });

        echo 'Got ' . count($flights) . ' flight(s)' . PHP_EOL;

        return $this->mapToModel($flights);
    }

    public function check(string $token, int $attempts = 1): ?FlightInfo
    {
        if ($attempts == 1) {
            echo "Checking flight...\n";
        }

        $params = [
            'v' => 2,
            'booking_token' => $token,
            'bnum' => 3,
            'pnum' => 1,
            'affily' => 'picky_{market}'
        ];
        $flight = $this->apiClient->fetch(self::FLIGHTS_CHECK_ENDPOINT . http_build_query($params));

        if (!$flight) {
            return null;
        }

        if (!$flight['flights_checked']) {
            if ($attempts >= self::CHECK_ATTEMPTS) {
                echo "Gave up after $attempts attempts\n";
                return null;
            }

            $attempts += 1;
            echo "Flight is unchecked. Try #$attempts attempt in 1 sec...\n";
            sleep(1);
            return $this->check($token, $attempts);
        }

        echo "Flight checked\n";

        return new FlightInfo($flight);
    }

    /**
     * @return Flight[]
     */
    private function mapToModel(array $flights): array
    {
        $entities = [];
        foreach ($flights as $flight) {
            $entities[] = new Flight($flight);
        }

        return $entities;
    }
}
