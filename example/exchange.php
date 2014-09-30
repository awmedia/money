<?php

/**
 * Money example page
 * @example: exchange
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Aw\Money\Money,
    Aw\Money\Currency;

$money1 = new Money(100, Currency::EUR);
$money2 = $money1->exchangeTo(Currency::GBP);
$money3 = $money1->exchangeTo(Currency::USD);

print strtr(":fromCurrency 1 is :toMoney \n", array(
        ':fromCurrency' => Currency::symbol($money1->getCurrency()),
        ':toMoney' => $money2->format()
    )
);

print strtr(":fromCurrency 1 is :toMoney \n", array(
        ':fromCurrency' => Currency::symbol($money1->getCurrency()),
        ':toMoney' => $money3->format()
    )
);