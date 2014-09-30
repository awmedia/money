<?php

/**
 * Money example page
 * @example: trace
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Aw\Money\Money,
    Aw\Money\Currency,
    Aw\Money\PriceCalculator;
    
    

//$r = 100 / 100 * 21;
//var_dump(bcdiv($r, 2));

$price = 1.15;
var_dump($price * 100);
var_dump(bcmul($price, 100));
exit;


    
     
/**
 * MarketPlace PriceCalculator
 */   
class MarketPlacePriceCalculator extends PriceCalculator
{
    protected $vatPercentage = 19;
    
    protected $sellerPrice;
    
    protected $shippingPrice;
    
    protected $commissionPercentage;
    
    protected $viewCurrency;
    
    public function __construct(Money $sellerPrice, Money $shippingPrice, $commissionPercentage = null, $viewCurrency = null)
    {
        $this->setSellerPrice($sellerPrice);
        $this->setShippingPrice($shippingPrice);
        $this->setCommissionPercentage($commissionPercentage);
        $this->setViewCurrency($viewCurrency);
    }
    
    /**
     * Getter/setter methods
     */
    
    public function setSellerPrice(Money $price)
    {
        $this->sellerPrice = $price;
        return $this;
    }
    
    public function getSellerPrice()
    {
        return $this->sellerPrice;
    }
    
    public function setShippingPrice(Money $price)
    {
        $this->shippingPrice = $price;
        return $this;
    }
    
    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }
    
    public function setCommissionPercentage($percentage)
    {
        $this->commissionPercentage;
        return $this;
    }
    
    public function getCommissionPercentage()
    {
        return $this->commissionPercentage;
    }
    
    public function setViewCurrency($currency)
    {
        $this->viewCurrency = $currency;
        return $this;
    }
    
    public function getViewCurrency()
    {
        return $this->viewCurrency;
    }
    
    public function getVatPercentage()
    {
        return $this->vatPercentage;
    }
    
    /**
     * Calculation methods
     */
     
     public function getCommissionPrice()
     {
         return $this->getSellerPrice()->percentage($this->getVatPercentage());
     }
    
     public function getWebsitePrice()
     {
         return $this->getSellerPrice()->add($this->getCommissionPrice());
     }
     
     public function getWebsiteViewPrice()
     {
         return $this->getWebsitePrice()->exchangeTo($this->getViewCurrency());
     }
     
     public function getCommissionVatPrice()
     {
         return $this->getCommissionPrice()->percentage($this->getVatPercentage());
     }
     
     public function getCommissionVatViewPrice()
     {
         return $this->getCommissionVatPrice()->exchangeTo($this->getViewCurrency());
     }
     
     public function getShippingViewPrice()
     {
         return $this->getShippingPrice()->exchangeTo($this->getViewCurrency());
     }
     
     public function getCheckoutTotalPrice()
     {
         return $this->getWebsitePrice()->add($this->getShippingPrice());
     }
     
     public function getCheckoutTotalViewPrice()
     {
         return $this->getWebsiteViewPrice()->add($this->getShippingViewPrice());
     }
     
     public function getCheckoutTotalVatPrice()
     {
         return $this->getCheckoutTotalPrice()->percentage($this->getVatPercentage());
     }
}

/**
 * Test drive
 */
$sellerPrice = Money::EUR(25000);
$shippingPrice = Money::EUR(695);
$commissionPercentage = 10;
$viewCurrency = Currency::GBP;
$calculator = new MarketPlacePriceCalculator($sellerPrice, $shippingPrice, $commissionPercentage, $viewCurrency);

echo "CHECKOUT PAGE:\n\n";
echo "CHECKOUT PAGE:\n\n";
print_r($calculator->getCheckoutTotalViewPrice()->getTrace(true));

