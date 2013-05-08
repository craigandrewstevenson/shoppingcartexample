<?php
namespace ShoppingCart\Model;

use ShoppingCart\Model\CartItem;
use Zend\Session\Container;

class ShoppingCart
{
    public $id;
    public $name;
    public $cartItems = array();
    
    public function __construct()
    {
        $this->name = "Hello, I am the shopping cart";
        $this->loadSession();
    }
 
    private function persitItems()
    {
        $itemsSession = new Container('cartItems');
        $itemsSession->items = $this->cartItems;
    }

    private function loadSession()
    {
        $itemsSession = new Container('cartItems');
        if (isset($itemsSession->items))
        {
            $this->cartItems = $itemsSession->items;
        }
    }

    public function addItem(Product $product, $quantity)
    {
        // get cart item from shopping cart if exists
        // add quantity to current quantity
        
        if ($this->cartItems[$product->id])
        {
            $curQuantity = $this->cartItems[$product->id]->quantity;
            $maxQuantity = $product->maxQuantity;
            if (($curQuantity + $quantity) > $maxQuantity)
            {
                $this->cartItems[$product->id]->quantity = $maxQuantity;
            }
            else
            {
                $this->cartItems[$product->id]->quantity += $quantity;
            }
        }
        else
        {
            $cartItem = new CartItem( $product, $quantity);
            $this->cartItems[$cartItem->productID] = $cartItem;
        }
        $this->persitItems();
    }

    public function updateItem($productID, $quantity)
    {
        if ($this->cartItems[$productID])
        {
            $this->cartItems[$productID]->quantity = $quantity;
        }
        $this->persitItems();
    }

    public function updateItemWithMaximumQuantity($productID)
    {
        if ($this->cartItems[$productID])
        {
            $this->cartItems[$productID]->quantity = $this->cartItems[$productID]->maxQuantity;
        }
        $this->persitItems();
    }

    public function removeItem($productID)
    {
        unset($this->cartItems[$productID]);
        $this->persitItems();
    }
  }
?>

