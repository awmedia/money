<?php

/**
 * Money example page
 * @example: labels
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Aw\Money\Money,
    Aw\Money\Currency;


$listingPrice = Money::EUR(20000)->addLabel('seller price');
$commissionPrice = Money::EUR(500)->addLabel('commission price');

$buyerPrice = $listingPrice->add($commissionPrice)->addLabel('buyer price');

$viewCurrency = Currency::GBP;
$exchangedBuyerPrice = $buyerPrice->exchangeTo($viewCurrency)->addLabel('buyer price in view currency');

$shippingPrice = Money::EUR(695)->addLabel('shipping price');
$checkoutPrice = $exchangedBuyerPrice->add($shippingPrice)->addLabel('checkout price');

print_r($checkoutPrice->getTrace(true));