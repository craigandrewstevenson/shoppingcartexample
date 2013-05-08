<?php
namespace ShoppingCart\Model;


class Product 
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $maxQuantity;
    public $rrp;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : NULL;
        $this->name = (isset($data['name'])) ? $data['name'] : NULL;
        $this->description = (isset($data['description'])) ? $data['description'] : NULL;
        $this->price = (isset($data['price'])) ? $data['price'] : NULL;
        $this->maxQuantity = (isset($data['maxQuantity'])) ? $data['maxQuantity'] : NULL;
        $this->rrp = (isset($data['rrp'])) ? $data['rrp'] : NULL;
    }

    public function getSavingsAmount()
    {
        return ($this->rrp - $this->price);
    }

    public function getSavingsPercentage()
    {
        return ($this->getSavingsAmount() / $this->rrp) * 100;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
?>
