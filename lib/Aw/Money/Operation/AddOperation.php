<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * AddOperation
 * @author Jerry Sietsma
 */
class AddOperation extends MoneyOperation
{
    protected $contraMoney;
    
    protected $preExchangedContraMoney;
    
    public function __construct(Money $money, Money $contraMoney)
    {
        $this->contraMoney = $contraMoney;
        
        parent::__construct($money);
        
        if ($this->getMoney()->getCurrency() !== $this->getContraMoney()->getCurrency() && $this->autoExchange)
        {
            $this->preExchangedContraMoney = $this->getContraMoney();
            $this->contraMoney = $this->getContraMoney()->exchangeTo($this->getMoney()->getCurrency());
        }
    }
    
    public function getContraMoney()
    {
        return $this->contraMoney;
    }
    
    protected function _calculate()
    {
        $amount = bcadd($this->getMoney()->getAmount(), $this->getContraMoney()->getAmount(), $this->getMoney()->getScale());
        return Money::construct($amount, $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates(), $this->getMoney()->getScale());
    }
    
    public function getTrace($format = false)
    {
        return array_merge(
            $this->preExchangedContraMoney !== null ? $this->getContraMoney()->getTrace($format) : array(),
            array(strtr('Added :amount by AddOperation :labels', array(
                ':amount' => $format ? $this->getContraMoney()->format() : $this->getContraMoney()->getAmount(),
                ':labels' => count($this->getContraMoney()->getLabels()) > 0 ? ' (' . implode(', ', $this->getContraMoney()->getLabels()) . ')' : ''
            )
        )));
    }
}