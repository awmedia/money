<?php

namespace Aw\Money;

//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Wallet
 * @author Jerry Sietsma
 */
class Wallet
{
    private $moneyCollection;
    
    protected $autoExchange = true;
    
    protected $primairyCurrency;
    
    public function __construct()
    {
        $this->moneyCollection = array();
    }
    
    public function getMoneyCollection()
    {
        return $this->moneyCollection;
    }
    
    public function add(Money $money)
    {
        if (!$this->primairyCurrency)
        {
            $this->primairyCurrency = $money->getCurrency();
        }
        else if(!$this->autoExchange && $money->getCurrency() !== $this->primairyCurrency)
        {
            throw new Exception('Not allowed to add Money objects with different currencies');
        }
        
        $this->moneyCollection[] = $money;
        
        return $this;
    }
    
    /**
     * Get amount of all Money instances in Wallet
     * @return  float   Total amount
     */
    public function getTotalMoney()
    {
        $totalMoney = new Money(0, $this->primairyCurrency);
        foreach ($this->getMoneyCollection() as $money)
        {
            $totalMoney->add($money);
        }
        return $totalMoney;
    }
}