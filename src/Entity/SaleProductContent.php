<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleProductContentRepository")
 */
class SaleProductContent
{   /**
 * @ORM\Id
 * @ORM\ManyToOne(targetEntity="App\Entity\Product",cascade={"merge"}))
 * @ORM\JoinColumn(name="product_id", referencedColumnName="id",)

 */
    private $product;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Sale"))
     * @ORM\JoinColumn(name="sale_id", referencedColumnName="id")
     */
    private $sale;
    /**
     * @ORM\Column(type="integer")
     */
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