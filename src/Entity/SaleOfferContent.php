<?php


namespace App\Entity;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleOfferContentRepository")
 */
class SaleOfferContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer",cascade={"persist","remove"},inversedBy="offers")
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id",)
     */
    private $offer;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sale"))
     * @ORM\JoinColumn(name="sale_id", referencedColumnName="id")
     */
    private $sale;
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricewhenbought;

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

    /**
     * @return mixed
     */
    public function getPricewhenbought()
    {
        return $this->pricewhenbought;
    }

    /**
     * @param mixed $pricewhenbought
     */
    public function setPricewhenbought($pricewhenbought): void
    {
        $this->pricewhenbought = $pricewhenbought;
    }

}