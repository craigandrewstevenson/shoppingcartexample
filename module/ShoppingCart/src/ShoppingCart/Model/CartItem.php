<?php

namespace ShoppingCart\Model;

use ShoppingCart\Model\Product;

class CartItem   
{
    public $productID;
    public $productName;
    public $quantity;
    public $price;
    public $rrp;
    public $percentageSaved;
    public $amountSaved;
    public $maxQuantity;
    
    public function __construct( $product, $quantity)
    {
        $this->productID = (int)$product->id;
        $this->quantity= $quantity;
        $this->refresh($product);
    }

    public function refresh($product)
    {
        $this->productName = $product->name;
        $this->price = $product->price;
        $this->percentageSaved = $product->getSavingsPercentage();
        $this->amountSaved = $product->getSavingsAmount();
        $this->rrp = $product->rrp;
        $this->maxQuantity = $product->maxQuantity;
    }

    public function getLineItemTotalCost()
    {
        return (int)$this->quantity * (float)$this->price;
    }

    public function getTotalAmountSaved()
    {
        return (int)$this->quantity * (float)$this->amountSaved;
    }

    public function getTotalRRP()
    {
        return (int)$this->quantity * (float)$this->rrp;
    }
}
?>


