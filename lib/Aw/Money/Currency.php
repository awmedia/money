<?php

namespace Aw\Money;

use Aw\Common\Enum;

/**
 * Currency Enum
 * @author Jerry Sietsma
 */
class Currency extends Enum
{
    const EUR = 'EUR';
    const GBP = 'GBP';
    const USD = 'USD';
    const SEK = 'SEK';
    const RUB = 'RUB';
    const NOK = 'NOK';
    const CZK = 'CZK';
    const CHF = 'CHF';
    const HUF = 'HUF';
    const PLN = 'PLN';
    const BGN = 'BGN';
    const RON = 'RON';
    const DIN = 'DIN';
    const AUD = 'AUD';
    const NZD = 'NZD';
    const KN = 'KN';
    const HRK = 'HRK';
    const DKK = 'DKK';
    const RSD = 'RSD';
    
    protected static $symbols = array(
        self::EUR => '€',
        self::GBP => '£',
        self::USD => '$',
        self::SEK => 'kr',
        self::RUB => 'rub',
        self::NOK => 'nok',
        self::CZK => 'czk',
        self::CHF => 'chf',
        self::HUF => 'huf',
        self::PLN => 'pln',
        self::BGN => 'bgn',
        self::RON => 'ron',
        self::DIN => 'din',
        self::AUD => 'aud',
        self::NZD => 'nzd',
        self::KN  => 'kn',
        self::HRK => 'hrk',
        self::DKK => 'dkk',
        self::RSD => 'rsd'
    );
    
    public static function symbol($currency)
    {
        return isset(self::$symbols[$currency]) ? self::$symbols[$currency] : '';
    }
    
    public static function isValid($currency)
    {
        return self::get($currency) !== null;
    }
}