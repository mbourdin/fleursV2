<?php


namespace App\Entity;

//PLACEHOLDER SANS BDD A REMPLACER DANS LE PROJET FINAL
use phpDocumentor\Reflection\Types\Boolean;

class Product
{
    private $id;
    private $nom;
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
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param null $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function equals(Product $p)
    {   return $this->id==$p->id;
    }
    public function toString()
    {   $str="/product/nom/".$this->getNom()."/id/".$this->getId();
        return $str;
    }
}