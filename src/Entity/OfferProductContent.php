<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferProductContentRepository")
 */
class OfferProductContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @var Offer|string
     * @ORM\ManyToOne(targetEntity="Offer", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $offer;
    public function getQuantity(): ?string
    {
        return $this->quantity;
    }
    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
    public function getPriceReal(): ?int
    {
        return $this->priceReal;
    }
    public function setPriceReal(?int $priceReal): self
    {
        $this->priceReal = $priceReal;
        return $this;
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
     * @return Offer|string
     */
    public function getOffer()
    {
        return $this->offer;
    }
    /**
     * @param Offer|string $offer
     */
    public function setOffer($offer): void
    {
        $this->offer = $offer;
    }
}