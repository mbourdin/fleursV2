<?php


namespace App\Entity;

//PLACEHOLDER SANS BDD A REMPLACER DANS LE PROJET FINAL
use phpDocumentor\Reflection\Types\Boolean;

class Service
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
    public function equals(Service $service)
    {   return $this->id==$service->id;
    }
    public function toString()
    {   $str="/service/id/".$this->id."/nom/".$this->nom;
        return $str;
    }

}