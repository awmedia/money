<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * PercentageOperation
 * @author Jerry Sietsma
 */
class PercentageOperation extends MoneyOperation
{
    protected $percentage;
    
    public function __construct(Money $money, $percentage)
    {
        $this->percentage = $percentage;
        
        parent::__construct($money);
    }
    
    public function getPercentage()
    {
        return $this->percentage;
    }
    
    protected function _calculate()
    {
        $amount = bcmul( bcdiv($this->getMoney()->getAmount(), 100, $this->getMoney()->getScale()), $this->getPercentage(), $this->getMoney()->getScale() );
        return new Money($amount, $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Percentage :percentage% by PercentageOperation', array(
                ':percentage' => $this->getPercentage()
            )
        ));
    }
}