<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 *
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $photopath;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * @ORM\ManyToMany(targetEntity="ProductType",inversedBy="products")
     * @ORM\JoinTable(name="products_types")
     */
    private $productTypes;


    /**
     *  @Vich\UploadableField(mapping="products_images",fileNameProperty="photopath")
     * @var File
     */
    protected $imagefile;

    /**
     * Product constructor.
     * @param $active
     */


    public function __construct()
    {
        $this->active = true;
        $this->productTypes= new ArrayCollection();
    }


    public function getId(): ?int
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPhotopath(): ?string
    {
        return $this->photopath;
    }

    public function setPhotopath(?string $photopath): self
    {
        $this->photopath = $photopath;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {   if ($price<=0) throw new \UnexpectedValueException("prix négatif");

        $this->price = $price;

        return $this;
    }
    public function equals(Product $p)
    {
        return $this->id==$p->id;
    }
    public function toString()
    {   $str="/product/id/"
        .$this->id
        ."/name/".$this->name;
        return $str;
    }

    /**
     * @return mixed
     */
    public function getProductTypes()
    {
        return $this->productTypes;
    }

    /**
     * @param mixed $productType
     */
    public function setProductTypes($productTypes): void
    {
        $this->productTypes = $productTypes;
    }

    /**
     * @return File
     */
    public function getImagefile(): ?File
    {
        return $this->imagefile;
    }

    /**
     * @param File $imagefile
     */
    public function setImagefile(File $imagefile): void
    {
        $this->imagefile = $imagefile;
        if( $this->imagefile instanceof UploadedFile)
        {   $this->updatedAt= new \DateTime();

        }
    }

    public function addType(ProductType $pt)
    {   if(! $this->productTypes->contains($pt))
        $this->productTypes->add($pt);
    }
    public function removeType(ProductType $pt)
    {   $this->productTypes->removeElement($pt);
    }


}
