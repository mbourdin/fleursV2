<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\City;
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
     * @ORM\ManyToOne(targetEntity="City", cascade={"merge"}, fetch="EAGER")
     */
    private $city;
//    /**
//     * @ORM\ManyToOne(targetEntity="AddressNumberAddition", cascade={"merge"}, fetch="EAGER")
//     */
//    private $addressnumberaddition;
}