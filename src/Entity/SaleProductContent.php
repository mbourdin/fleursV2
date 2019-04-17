<?php


namespace App\Entity;


class SaleProductContent
{   private $product;
    private $sale;
    private $quantity;

    /**
     * SaleProductContent constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * @param mixed $sale
     */
    public function setSale($sale)
    {
        $this->sale = $sale;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function toString()
    {   $str="/productcontent/".$this->product->toString()."/quantity/".$this->quantity;
        return $str;
    }
}