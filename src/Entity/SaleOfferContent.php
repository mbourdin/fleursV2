<?php


namespace App\Entity;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
class SaleOfferContent
{   private $offer;
    private $sale;
    private $quantity;

    /**
     * SaleOfferContent constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     */
    public function setOffer(Offer $offer): void
    {
        $this->offer = $offer;
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
    public function setSale(Sale $sale): void
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
    public function setQuantity(int $quantity): void
    {   if ($quantity<=0) throw new UnexpectedValueException("quantité négative");
        $this->quantity = $quantity;
    }
    public function toString()
    {   $str="/offercontent/".$this->offer->toString()."/quantity/".$this->quantity;
        return $str;
    }
}