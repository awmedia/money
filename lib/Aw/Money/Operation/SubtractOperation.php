<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * SubtractOperation
 * @author Jerry Sietsma
 */
class SubtractOperation extends MoneyOperation
{
    protected $contraMoney;
    
    public function __construct(Money $money, Money $contraMoney)
    {
        $this->contraMoney = $contraMoney;
        
        parent::__construct($money);
        
        if ($this->getMoney()->getCurrency() !== $this->getContraMoney()->getCurrency() && $this->autoExchange)
        {
            $this->contraMoney = $this->getContraMoney()->exchangeTo($this->getMoney()->getCurrency());
        }
    }
    
    public function getContraMoney()
    {
        return $this->contraMoney;
    }
    
    protected function _calculate()
    {
        $amount = bcsub($this->getMoney()->getAmount(), $this->getContraMoney()->getAmount(), $this->getMoney()->getScale());
        return new Money($amount, $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates(), $this->getMoney()->getScale());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Subtracted :amount by SubtractOperation :labels', array(
                ':amount' => $format ? $this->getContraMoney()->format() : $this->getContraMoney()->getAmount(),
                ':labels' => count($this->getContraMoney()->getLabels()) > 0 ? ' (' . implode(', ', $this->getContraMoney()->getLabels()) . ')' : ''
            )
        ));
    }
}