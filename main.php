<?php

use Nzulfigarov\CheckDatePeriods\PeriodChecker;

require 'vendor/autoload.php';

$periods = $argv[1] ?? null;

if ($periods === null) {
    throw new \InvalidArgumentException('Invalid period');
}


$checker = new PeriodChecker(explode(',', $periods));

$result = $checker->checkForCurrentDay();

echo "\n\r-----Result-------\n\r";
echo sprintf("Previous check date: %s\n\r", array_shift($result));
echo sprintf("Is current day is check day?: %s\n\r", array_shift($result) === false ? 'NO' : 'YES');
echo sprintf("Next check day: %s\n\r", array_shift($result));