<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * MultiplyOperation
 * @author Jerry Sietsma
 */
class MultiplyOperation extends MoneyOperation
{
    protected $multiplyBy;
    
    public function __construct(Money $money, $multiplyBy)
    {
        $this->multiplyBy = $multiplyBy;
        
        parent::__construct($money);
    }
    
    public function getMultiplyBy()
    {
        return $this->multiplyBy;
    }
    
    protected function _calculate()
    {
        $amount = bcmul($this->getMoney()->getAmount(), $this->multiplyBy, $this->getMoney()->getScale());
        return new Money($amount, $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates(), $this->getMoney()->getScale());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Multiply by :multiplyBy by MultiplyOperation', array(
                ':multiplyBy' => $this->getMultiplyBy()
            )
        ));
    }
}