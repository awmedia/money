<?php

namespace Aw\Money;

use Aw\Money\Operation\AddOperation,
    Aw\Money\Operation\SubtractOperation,
    Aw\Money\Operation\ExchangeOperation,
    Aw\Money\Operation\RoundOperation,
    Aw\Money\Operation\DivideOperation,
    Aw\Money\Operation\MultiplyOperation,
    Aw\Money\Operation\PercentageOperation,
    Aw\Common\Utils\ArrayUtils as Arr;

/**
 * Money
 * @author Jerry Sietsma
 */
class Money
{
    private static $defaultCurrency = Currency::EUR;
    
    private static $defaultScale = 2;
    
    private $amount;
    
    private $currency;
    
    private $labels;
    
    public static function setDefaultCurrency($currency)
    {
        self::$defaultCurrency;
    }
    
    public static function setDefaultExchangeRates(ExchangeRates $rates)
    {
        self::$defaultExchangeRates = $rates;
    }
    
    public static function setDefaultScale($scale)
    {
        self::$scale = $scale;
    }
    
    /**
     * Factory method to instantiate new Money like Money::construct(100, Currency::EUR);
     * Usefull for method chaining
     */
    public static function construct()
    {
        $class = new \ReflectionClass(get_called_class());
        return $class->newInstanceArgs(func_get_args());
    }
    
    /**
     * Factory method to instantiate new Money like Money::EUR(100);
     */
    public static function __callStatic($method, $arguments)
    {
        $class = new \ReflectionClass(get_called_class());
        return $class->newInstanceArgs(array($arguments[0], Currency::get($method), Arr::get($arguments, 1), Arr::get($arguments, 2, array())));
    }
    
    /**
     * @param   mixed   Amount in cents
     * @param   string  Currency enum value (optional)
     * @param   object  ExchangeRates instance or null to use lates rates
     */
    public function __construct($amount, $currency = null, ExchangeRates $rates = null, $scale = null)
    {
        $this->amount = $amount;
        $this->currency = $currency ?: self::$defaultCurrency;
        $this->exchangeRates = $rates ?: ExchangeRatesMgr::getLatest();
        $this->labels = array();
        
        $this->setScale($scale ?: self::$defaultScale);

        if (!Currency::isValid($this->currency))
        {
            throw new Exception('Currency "' . $this->currency . '" is not a valid currency.');
        }
    }
    
    /**
     * Public methods
     */
    
    /**
     * Set scale
     * @param   int     Scale
     * @return  object  $this
     */
    public function setScale($scale)
    {
        $this->scale = (int) $scale;
        return $this;
    }
    
    /**
     * Get scale
     * @return  int Scale
     */
    public function getScale()
    {
        return $this->scale;
    }
    
    /**
     * Get amount
     * @return  mixed   amount in cents
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Get exchange rates
     * @return  object  ExchangeRates instance
     */
    public function getExchangeRates()
    {
        return $this->exchangeRates;
    }
    
    /**
     * Format money amount
     * @return  string  Formatted money amount
     */
    public function format()
    {
        // setlocale(LC_MONETARY, 'en_US');
        return Currency::symbol($this->getCurrency()) . ' ' . number_format($this->getAmount() / 100, 2);
    }
    
    /**
     * Get currency
     * @return  string  Currency enum value
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function __toString()
    {
        return $this->format();
    }
    
    /**
     * Get trace
     * @param   bool    True to format money amounts in trace, false to use raw amounts
     * @return  array   Array with trace
     */
    public function getTrace($format = false)
    {
        return array(strtr('Money :amount :labels', array(
            ':amount' => $format ? $this->format() : $this->getAmount(), 
            ':labels' => count($this->getLabels()) > 0 ? ' (' . implode(', ', $this->getLabels()) . ')' : ''
        )));
    }
    
    /**
     * Get labels
     * Use this method to add labels to the Money object
     * @return  array
     */
    public function getLabels()
    {
        return $this->labels;
    }
    
    /**
     * Add label
     * @param   string  Label
     * @return  object  $this
     */
    public function addLabel($label)
    {
        $this->labels[] = $label;
        return $this;
    }
    
    /**
     * Compare methods
     */
    
    /**
     * Is this amount more than given Money amount?
     * @return  boolean True if this is more, false if not
     */
    public function isMore(Money $money)
    {
        return $this->compare($money) === 1;
    }
    
    /**
     * Is this amount less than given Money amount?
     * @return  boolean True if this is less, false if not
     */
    public function isLess(Money $money)
    {
        return $this->compare($money) === -1;
    }
    
    /**
     * Is this equal to the given Money amount?
     * @return  boolean True if this is equal, false if not
     */
    public function isEqual(Money $money)
    {
        return $this->isCurrencyEqual($money) && $this->getAmount() === $money->getAmount();
    }
    
    /**
     * Compare
     * @param   object  Money object
     * @return  int     -1 if less, 0 if equal and 1 if more
     */
    public function compare(Money $money) 
    {
        if ($this->isCurrencyEqual($money))
        {
            throw new Exception('Cannot compare two Money objects with different currencies (' . $this->getCurrency() . ', ' . $money->getCurrency() . ').');
        }
        
        return ($this->getAmount() < $money->getAmount()) ? -1 : ((int) $this->getAmount() > $money->getAmount());
    }
    
    /**
     * Is currency equal
     * @param   object  Money
     * @return  bool    True if currency is equal, false if not
     */
    private function isCurrencyEqual(Money $money)
    {
        return $this->getCurrency() === $money->getCurrency();
    }
    
    /**
     * Equalize currency
     * @param   object  Money instance in a different currency
     * @return  object  Money instance in converted currency
     */
    public function equalizeCurrency(Money $money)
    {
        return $money->exchangeTo($this->getCurrency());
    }
    
    /**
     * Money operation methods
     */
    
    public function add(Money $money)
    {
        return new MutatedMoney(new AddOperation($this, $money), $this->getCurrency(), $this->getExchangeRates());
    }
    
    public function subtract(Money $money)
    {
        return new MutatedMoney(new SubtractOperation($this, $money), $this->getCurrency(), $this->getExchangeRates());
    }
    
    public function divide($divideBy)
    {
        return new MutatedMoney(new DivideOperation($this, $divideBy), $this->getCurrency(), $this->getExchangeRates());
    }
    
    public function multiply($multiplyBy)
    {
        return new MutatedMoney(new MultiplyOperation($this, $multiplyBy), $this->getCurrency(), $this->getExchangeRates());
    }
    
    public function exchangeTo($toCurrency)
    {
        return new MutatedMoney(new ExchangeOperation($this, $toCurrency), $toCurrency, $this->getExchangeRates());
    }
    
    /*
    public function round($precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        return new MutatedMoney(new RoundOperation($this, $precision, $mode), $this->getCurrency(), $this->getExchangeRates());
    }
    */
    
    public function percentage($percentage)
    {
        return new MutatedMoney(new PercentageOperation($this, $percentage), $this->getCurrency(), $this->getExchangeRates());
    }
}