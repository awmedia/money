<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * DivideOperation
 * @author Jerry Sietsma
 */
class DivideOperation extends MoneyOperation
{
    protected $divideBy;
    
    public function __construct(Money $money, $divideBy)
    {
        $this->divideBy = $divideBy;
        
        parent::__construct($money);
    }
    
    public function getDivideBy()
    {
        return $this->divideBy;
    }
    
    protected function _calculate()
    {
        $amount = bcdiv($this->getMoney()->getAmount(), $this->divideBy, $this->getMoney()->getScale());
        return new Money($amount, $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates(), $this->getMoney()->getScale());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Divided by :divideBy by DivideOperation', array(
                ':divideBy' => $this->getDivideBy()
            )
        ));
    }
}