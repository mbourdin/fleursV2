<?php


namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UnexpectedValueException;

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
     * @ORM\OneToMany(targetEntity="App\Entity\OfferProductContent",mappedBy="offer",cascade={"persist","merge"},fetch="LAZY")
     * @ORM\JoinTable(name="offer_product_content")
     */
    private $products;

    /**
     * Offer constructor.
     */
    public function __construct()
    {   $this->id=null;
        $this->active=true;
        $this->products = new ArrayCollection();
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
    public function setName(string $name)
    {
        $this->name = $name;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(string $description): void
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
    public function setActive(bool $active): void
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
    public function setStart(\DateTime $start): void
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
    public function setEnd(\DateTime $end): void
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
    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }
    public function price()
    {   return $this->sum()-$this->intDiscount();
    }
    private function sum()
    {    $sum=0;
        foreach ($this->products->getIterator() as $i => $productContent) {
            $sum+=$productContent->getProduct()->getPrice();
        }
        return $sum;

    }
    public function intDiscount()
    {   return ($this->sum()*$this->discount)/100;

    }
    public function addProduct(Product $product, int $quantity)
    {
        if ($quantity <= 0) throw new UnexpectedValueException("quantité négative");
        foreach ($this->products->getIterator() as $i => $productContent) {
            if ($product->equals($productContent->getProduct())) {
                $productContent->setQuantity($quantity);
                return true;
            }
        }
        $newProduct = new OfferProductContent();
        $newProduct->setProduct($product);
        $newProduct->setOffer($this);
        $newProduct->setQuantity($quantity);
        $this->products->add($newProduct);

        return true;
    }

    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }
    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }


}