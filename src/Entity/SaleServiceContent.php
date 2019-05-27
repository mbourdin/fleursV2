<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleServiceContentRepository")
 */
class SaleServiceContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Service",cascade={"merge"})
 */
    private $service;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sale",inversedBy="services",cascade={"merge"})
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
     * SaleServiceContent constructor.
     */
    public function __construct()
    {
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
    {   if($quantity<=0)throw new \UnexpectedValueException("quantité négative");
        $this->quantity = $quantity;
        return $this;
    }

    public function toString()
    {   $str="/servicecontent/".$this->service->toString()."/quantity/".$this->quantity;
        return $str;
    }
    //Cette fonction ne doit etre utilisée que pour la valeur de retour du controleur Sale lors de l'effacement
    public function forceSetToZero()
    {
        $this->quantity=0;
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

}