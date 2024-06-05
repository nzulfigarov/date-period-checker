<?php

namespace Nzulfigarov\CheckDatePeriods;

use Carbon\Carbon;

class PeriodChecker
{

    protected array $dates = [];
    public function __construct(array $dates)
    {
        $dates = $this->validate($dates);
        $this->dates = $dates;
    }

    protected function validate(array $dates): array
    {
        $validated = [];
        foreach ($dates as $dateNotValidated) {
            $date = trim($dateNotValidated);
            if (preg_match('/\d{2}[.]\d{2}/', $date)) {
                $validated[] = $date;
            }
        }

        return $validated;
    }

    public function checkForCurrentDay()
    {
        $now = Carbon::now()->startOfDay();
        $reference = $this->createThreeYearPeriods($this->dates);
        //prev
        $prevValue = null;
        //next
        $nextValue = null;
        //today is the check day
        $todayIsTheCheckDay = false;

        echo sprintf("Checking date: %s. \n\rReference: %s ", $now->format('d.m.y'), implode(',', $this->dates));

        foreach ($reference as $stringDate => $date) {
            if (!$date instanceof Carbon) {
                throw new InvalidArgumentException('Unreacheble statement');
            }

            if ($date->lessThan($now)) {
                $prevValue = $stringDate;
            } elseif ($nextValue === null && $date->greaterThan($now)) {
                $nextValue = $stringDate;
            } elseif ($date->equalTo($now)) {
                $todayIsTheCheckDay = true;
            }
        }

        return [$prevValue, $todayIsTheCheckDay, $nextValue];
    }

    protected function createThreeYearPeriods(array $dates)
    {
        $threeYearRange = [
            Carbon::now()->subYear()->year,
            Carbon::now()->year,
            Carbon::now()->addYear()->year,
        ];
        $threeYearsPeriods = [];

        foreach ($threeYearRange as $year) {
            $threeYearsPeriods = array_merge($threeYearsPeriods, $this->createOneYearPeriod($year, $dates));
        }

        return $threeYearsPeriods;
    }

    protected function createOneYearPeriod(int $year, array $dates): array
    {
        $oneYearPeriod = [];

        foreach ($dates as $dateMonth) {
            $fullDate = Carbon::createFromFormat('d.m.Y', sprintf('%s.%s', $dateMonth, $year));
            if ($fullDate === null) {
                throw new \InvalidArgumentException('Invalid date');
            }
            $startOfTheDay = $fullDate->startOfDay();
            $oneYearPeriod[$startOfTheDay->format('d.m.Y')] = $startOfTheDay;
        }

        return $oneYearPeriod;
    }
}