<?php

namespace Aw\Money\Operation;

use Aw\Money\Money;

/**
 * RoundOperation
 * @author Jerry Sietsma
 */
class RoundOperation extends MoneyOperation
{
    protected $precision;
    
    protected $mode;
    
    public function __construct(Money $money, $precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        $this->precision = $precision;
        $this->mode = $mode;
        
        parent::__construct($money);
    }
    
    public function getPrecision()
    {
        return $this->precision;
    }
    
    public function getMode()
    {
        return $this->mode;
    }
    
    protected function _calculate()
    {
        return new Money(round($this->getMoney()->getAmount(), $this->getPrecision(), $this->getMode()), $this->getMoney()->getCurrency(), $this->getMoney()->getExchangeRates());
    }
    
    public function getTrace($format = false)
    {
        return array(strtr('Rounded by RounderOperation with a precision of :precision and mode :mode', array(
                ':amount' => $format ? $this->getMoney()->format() : $this->getMoney()->getAmount(),
                ':precision' => $this->getPrecision(),
                ':mode' => $this->getMode()
            )
        ));
    }
}