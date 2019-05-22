<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleProductContentRepository")
 */
class SaleProductContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Product",cascade={"merge"})
 * @ORM\JoinColumn(name="product_id", referencedColumnName="id",)

 */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sale",inversedBy="products",cascade={"merge"})
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
     * SaleProductContent constructor.
     */
    public function __construct()
    {   $this->id=null;
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
    {   if($quantity<=0)throw new \UnexpectedValueException();
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function toString()
    {   $str="/productcontent/".$this->product->toString()."/quantity/".$this->quantity;
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
    //Cette fonction ne doit etre utilisée que pour la valeur de retour du controleur Sale lors de l'effacement
    public function forceSetToZero()
    {
        $this->quantity=0;
    }

}