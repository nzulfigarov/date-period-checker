<?php

use Nzulfigarov\CheckDatePeriods\PeriodChecker;

require 'vendor/autoload.php';

$periods = $argv[1] ?? null;

if ($periods === null) {
    throw new \InvalidArgumentException('Invalid period');
}


$checker = new PeriodChecker(explode(',', $periods));

$result = $checker->checkForCurrentDay();
dd($result);