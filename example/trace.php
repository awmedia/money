<?php

/**
 * Money example page
 * @example: trace
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Aw\Money\Money,
    Aw\Money\Currency;

$money1 = new Money(100, Currency::EUR);
$money2 = $money1->exchangeTo(Currency::GBP);
$money3 = $money2->exchangeTo(Currency::USD);
$money4 = $money3->add(new Money(2500, Currency::USD));
$money5 = $money4->divide(2);
$money6 = $money5->multiply(4);
$money7 = $money6->exchangeTo(Currency::EUR);
$money8 = $money7->add(new Money(30000, Currency::GBP));

print_r($money8->getTrace(true));
