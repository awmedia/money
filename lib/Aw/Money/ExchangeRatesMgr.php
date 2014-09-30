<?php

namespace Aw\Money;

/**
 * ExchangeRatesMgr
 * Manager
 * @author Jerry Sietsma
 */
class ExchangeRatesMgr
{
    public static function getLatest()
    {
        return new ExchangeRates(array(
            Currency::EUR => 1,
            Currency::GBP => 0.781,
            Currency::USD => 1.2737
        ));
    }
}