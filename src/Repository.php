<?php

namespace app;

use app\entities\Flight;

class Repository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllUpcoming(): \PDOStatement
    {
        $sql = '
            SELECT id, booking_token
            FROM flights
            WHERE departure_time > :time
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'time' => date('Y-m-d H:i:s')
        ]);

        return $statement;
    }

    public function getById(string $id): ?array
    {
        $sql = '
            SELECT id
            FROM flights
            WHERE id = :id
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id' => $id
        ]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function insert(Flight $flight): bool
    {
        $sql = '
            INSERT INTO flights
            VALUES (:id, :from, :to, :departure_time, :arrival_time, :price, :booking_token)
        ';

        return $this->pdo->prepare($sql)->execute([
            'id' => $flight->getId(),
            'from' => $flight->getFrom(),
            'to' => $flight->getTo(),
            'departure_time' => $this->prepareDateTime($flight->getDepartureTime()),
            'arrival_time' => $this->prepareDateTime($flight->getArrivalTime()),
            'price' => $flight->getPrice(),
            'booking_token' => $flight->getBookingToken()
        ]);
    }

    public function updatePrice(Flight $flight): bool
    {
        $sql = '
            UPDATE flights
            SET price = :price
            WHERE id = :id
        ';

        return $this->pdo->prepare($sql)->execute([
            'id' => $flight->getId(),
            'price' => $flight->getPrice()
        ]);
    }

    public function delete(Flight $flight): bool
    {
        $sql = '
            DELETE FROM flights
            WHERE id = :id
        ';

        return $this->pdo->prepare($sql)->execute([
            'id' => $flight->getId()
        ]);
    }

    private function prepareDateTime(\DateTimeImmutable $dateTime): string
    {
        return $dateTime->format('Y-m-d H:i:s');
    }
}
