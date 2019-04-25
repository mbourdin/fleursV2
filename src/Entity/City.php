<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City {
    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $inseeid;
    /**
     * City constructor.
     */
    public function __construct()
    {   $this->active=true;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
    public function setId(int $id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getInseeid()
    {
        return $this->inseeid;
    }
    /**
     * @param mixed $inseeid
     */
    public function setInseeid(int $inseeid)
    {
        $this->inseeid = $inseeid;
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
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function activeString()
    {   if($this->active) return "active";
        return "inactive";

    }

}