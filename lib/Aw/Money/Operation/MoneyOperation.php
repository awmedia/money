<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * MoneyOperation
 * @author Jerry Sietsma
 */
abstract class MoneyOperation
{
    protected $money;
    
    /**
     * @cfg bool    True to exchange different currencies without noticing, false to throw an Aw\Money\Exception
     * @note:       This should be implemented in subclasses
     */
    protected $autoExchange = true;
    
    public function __construct(Money $money)
    {
        $this->money = $money;
    }
    
    public function getMoney()
    {
        return $this->money;
    }
    
    public function calculate()
    {
        return $this->_calculate();
    }
    
    abstract protected function _calculate();
    
    abstract public function getTrace();
}