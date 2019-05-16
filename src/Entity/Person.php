<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use FOS\UserBundle\Model\User as FOSUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Address;
use UnexpectedValueException;

use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 *@Vich\Uploadable()
 */
#J'ai fait étendre UserInterface à mon entité person.
class Person extends FOSUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationdate;

    public function __construct()
    {
        parent::__construct();
        $this->rights=1;
        $this->setEmailCanonical(null);
        $this->deleted=false;
        $this->banned=false;
        $this->addresses=new ArrayCollection();
        $this->setRoles(["ROLE_USER"]);
    }
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $firstname;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthday;

    /**
     * @ORM\Column(type="integer")
     */
    private $rights;

    /**
     *  @Vich\UploadableField(mapping="persons_images",fileNameProperty="photopath")
     * @var File
     */
    protected $imagefile;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $photopath;
    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;
    /**
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @ORM\ManyToMany(targetEntity="Address")
     * @ORM\JoinTable(name="persons_addresses")
     */
    private $addresses;

    /**
     * @return mixed
     */

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
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }
    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }
    /**
     * @ORM\ManyToOne(targetEntity="Address", cascade={"merge"}, fetch="LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $address;
    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param mixed $rights
     */
    public function setRights(int $rights=1): void
    {   if($rights<0 || $rights>7)
        {
            throw new UnexpectedValueException("valeur invalide dans Person::SetRights");
        }
        $this->rights = $rights;
        $this->setRolesInt($rights);
    }
    private function setRolesInt(int $rights)
    {   if(($rights%2)==1)
        {
            $this->addRole("ROLE_BUYER");
        }
        else
        {
            $this->removeRole("ROLE_BUYER");
        }

        if(($rights/2%2)==1)
        {
            $this->addRole("ROLE_ADMIN");
        }
        else
        {
            $this->removeRole("ROLE_ADMIN");
        }
        if(($rights/4%2)==1)
        {
            $this->addRole("ROLE_ACC_ADMIN");
        }
        else
        {
            $this->removeRole("ROLE_ACC_ADMIN");
        }
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

    /**
     * @return mixed
     */
    public function getPhotopath()
    {
        return $this->photopath;
    }

    /**
     * @param mixed $photopath
     */
    public function setPhotopath($photopath): void
    {
        $this->photopath = $photopath;
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
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
     * @param mixed $creationdate
     */
    public function setCreationdate($creationdate): void
    {
        $this->creationdate = $creationdate;
    }


    public function getEmailCanonical()
    {
        return parent::getEmailCanonical(); // TODO: Change the autogenerated stub
    }

    public function setEmailCanonical($emailCanonical)
    {
        return parent::setEmailCanonical($emailCanonical); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @param mixed $banned
     */
    public function setBanned($banned): void
    {
        $this->banned = $banned;
    }
    public function equals(Person $person)
    {
        return $person->id==$this->id;
    }
    public function addAddress(Address $address)
    {   if(! $this->addresses->contains($address))
        $this->addresses->add($address);
    }
    public function removeAddress(Address $address)
    {   $this->addresses->removeElement($address);
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     */
    public function setAddresses($addresses): void
    {
        $this->addresses = $addresses;
    }

    public function eraseCredentials()
    {
        $this->password=null;
    }

}