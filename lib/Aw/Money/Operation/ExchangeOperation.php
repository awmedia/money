<?php

namespace Aw\Money\Operation;

use Aw\Money\Money,
    Aw\Money\Currency;

/**
 * ExchangeOperation
 * @author Jerry Sietsma
 */
class ExchangeOperation extends MoneyOperation
{
    protected $toCurrency;
    
    public function __construct(Money $money, $toCurrency)
    {
        parent::__construct($money);

        $this->toCurrency = $toCurrency;
        
        if (!Currency::isValid($this->toCurrency))
        {
            throw new Exception('Currency "' . $this->toCurrency . '" is not a valid currency.');
        }
    }
    
    public function getToCurrency()
    {
        return $this->toCurrency;
    }
    
    protected function _calculate()
    {
        return $this->getMoney()->getExchangeRates()->exchangeTo($this->getMoney(), $this->getToCurrency());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Exchange to :toCurrency by ExchangeOperation', array(
                ':toCurrency' => $this->getToCurrency()
            )
        ));
    }
}