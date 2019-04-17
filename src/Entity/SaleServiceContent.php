<?php


namespace App\Entity;


class SaleServiceContent
{   private $service;
    private $sale;
    private $quantity;

    /**
     * SaleServiceContent constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
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
    {   $str="/servicecontent/".$this->service->toString()."/quantity/".$this->quantity;
        return $str;
    }
}