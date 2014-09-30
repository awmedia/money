<?php

namespace Aw\Money;

/**
 * ExchangeRates
 * @Todo: refactor
 * @author Jerry Sietsma
 */
class ExchangeRates
{
    protected $rates;
    
    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }
    
    public function exchangeTo(Money $money, $toCurrency)
    {
        $exchangedMoney = $money;

        if ($money->getCurrency() !== $toCurrency)
        {
            $exchangeRatio = $this->getExchangeRatio($money->getCurrency(), $toCurrency, $money->getScale());
            $amount = bcmul($money->getAmount(), $exchangeRatio, $money->getScale());
            
            $exchangedMoney = new Money($amount, $toCurrency, $this, $money->getScale());
        }
        
        return $exchangedMoney;
    }
    
    /**
     * Private/protected methods
     */
    
    protected function getExchangeRatio($fromCurrency, $toCurrency, $scale)
    {
        return 1.0 / $this->getExchangeRate($fromCurrency) * $this->getExchangeRate($toCurrency);
    }
    
    protected function getExchangeRate($currency)
    {
        if (!isset($this->rates[$currency]))
        {
            if (!Currency::isValid($currency))
            {
                throw new Exception('Currency "' . $currency . '" is not a valid currency');
            }
            else
            {
                throw new Exception('No exchange rates for currency "' . $currency . '"');
            }
        }
        
        return $this->rates[$currency];
    }
}