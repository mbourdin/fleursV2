<?php
/**
 * Created by PhpStorm.
 * User: JOEL
 * Date: 24/04/2019
 * Time: 09:26
 */
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class ServiceProductContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quantity;
    /**
     * @var Product|string
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $product;
    /**
     * @var Service|string
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE" )
     */
    private $service;
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
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
    /**
     * @return Product|string
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * @param Product|string $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }
    /**
     * @return Service|string
     */
    public function getService()
    {
        return $this->service;
    }
    /**
     * @param Service|string $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }
}