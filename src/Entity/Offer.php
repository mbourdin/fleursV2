<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

//PLACEHOLDER SANS BDD A REMPLACER DANS LE PROJET FINAL
/**
* @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
*/
class Offer
{   /**
 * @ORM\Id
 * @ORM\GeneratedValue
 * @ORM\Column(type="integer")
 */
    private $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photopath;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;
    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="integer")
     */
    private $discount;
    /**
     * Product constructor.
     */
    public function __construct()
    {   $this->id=null;
        $this->nom=null;
    }


    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $nom
     */
    public function setName($name)
    {
        $this->nom = $name;
    }
    public function equals(Offer $o)
    {   return $this->id==$o->id;
    }
    public function toString()
    {   $str="/offer/id/".$this->id."/nom/".$this->name;
        return $str;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }


    /**
     * @return mixed
     */
    public function getPhotopath()
    {
        return $this->photopath;
    }

    /**
     * @param mixed $photopath
     */
    public function setPhotopath($photopath): void
    {
        $this->photopath = $photopath;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start): void
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end): void
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return null
     */
    public function getNom()
    {
        return $this->nom;
    }

}