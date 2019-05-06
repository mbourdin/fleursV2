<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $number;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $roadtype;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $roadname;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $additionaladdress;
    /**
     * @ORM\Column(type="string", length=6)
     */
    private $postalcode;
    /**
     * @ORM\Column(type="integer")
     */
    private $cityId;
    /**
     * @ORM\Column(type="string",length=10)
     */
    private $numberaddition;

    /**
     * Address constructor.
     */
    public function __construct()
    {
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

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getRoadtype()
    {
        return $this->roadtype;
    }

    /**
     * @param mixed $roadtype
     */
    public function setRoadtype($roadtype): void
    {
        $this->roadtype = $roadtype;
    }

    /**
     * @return mixed
     */
    public function getRoadname()
    {
        return $this->roadname;
    }

    /**
     * @param mixed $roadname
     */
    public function setRoadname($roadname): void
    {
        $this->roadname = $roadname;
    }

    /**
     * @return mixed
     */
    public function getAdditionaladdress()
    {
        return $this->additionaladdress;
    }

    /**
     * @param mixed $additionaladdress
     */
    public function setAdditionaladdress($additionaladdress): void
    {
        $this->additionaladdress = $additionaladdress;
    }

    /**
     * @return mixed
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param mixed $postalcode
     */
    public function setPostalcode($postalcode): void
    {
        $this->postalcode = $postalcode;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId): void
    {
        $this->cityId = $cityId;
    }



    /**
     * @return mixed
     */
    public function getNumberaddition()
    {
        return $this->numberaddition;
    }

    /**
     * @param mixed $numberaddition
     */
    public function setNumberaddition($numberaddition): void
    {
        $this->numberaddition = $numberaddition;
    }

}