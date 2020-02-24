<?php

namespace app;

class Utils
{
    public static function getDatesForMonth(): array
    {
        $period = new \DatePeriod(new \DateTime(), new \DateInterval('P1D'), (new \DateTime())->modify('+1 month'));

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('d/m/Y');
        }

        return $dates;
    }
}
