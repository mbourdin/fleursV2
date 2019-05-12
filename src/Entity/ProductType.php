<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="App\Repository\ProducttypeRepository")
 */
class ProductType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photopath;


    /**
     *  @Vich\UploadableField(mapping="productTypes_images",fileNameProperty="photopath")
     * @var File
     */
    protected $imagefile;
    /**
     * Producttype constructor.
     */
    public function __construct()
    {   $this->active=true;
    }

    public function getId(): ?int
    {
        return $this->id;
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



    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
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
    public function setImagefile(?File $imagefile): void
    {
        $this->imagefile = $imagefile;
    }


}
