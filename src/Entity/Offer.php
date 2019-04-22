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
    public function getPrice()
    {
        return 0;
    }
}