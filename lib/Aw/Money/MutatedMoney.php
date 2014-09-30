<?php

namespace Aw\Money;

use Aw\Money\Operation\MoneyOperation;

/**
 * MutatedMoney
 * @author Jerry Sietsma
 */
class MutatedMoney extends Money
{
    protected $operation;
    
    public function __construct(MoneyOperation $operation, $currency, ExchangeRates $rates = null, $scale = null)
    {
        $this->operation = $operation;
        
        parent::__construct(0, $currency, $rates, $scale);
    }
    
    public function getAmount()
    {
        return $this->getOperation()->calculate()->getAmount();
    }
    
    public function getOperation()
    {
        return $this->operation;
    }
    
    public function getTrace($format = false)
    {
        return array_merge(
            $this->getOperation()->getMoney()->getTrace($format),
            $this->getOperation()->getTrace($format),
            array(strtr('MutatedMoney :amount :labels', array(
                ':amount'=>$format ? $this->format() : $this->getAmount(),
                ':labels' => count($this->getLabels()) > 0 ? ' (' . implode(', ', $this->getLabels()) . ')' : ''
            )))
        );
    }
}