<?php

/**
 * Money example page
 * @example: basic
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Aw\Money\Money,
    Aw\Money\Currency;

// Add
$money1 = new Money(100, Currency::EUR);
$money2 = new Money(200, Currency::EUR);
$money3 = $money1->add($money2);
var_dump($money3->format());

// Subtract
$money4 = $money2->subtract($money1);
var_dump($money4->format());

// Percentage
$money5 = $money4->percentage(21);
var_dump($money5->format());


print_r($money5->getTrace(true));