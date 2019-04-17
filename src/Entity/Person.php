<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use App\Interfaces\HasPhotopath;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person extends FOSUser implements HasPhotopath{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $firstname;
    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

//      Défini dans la classe mère
//    /**
//     * @ORM\Column(type="string",length=50)
//     */
//    protected $email;


//      Défini dans la classe mère
//    /**
//     * @ORM\Column(type="string", length=32)
//     */
//    protected $password;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $photopath;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @ORM\Column(type="boolean")
     */
    private $chatbanned;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

//      remplacé par l'attribut hérité de la classe parente : enabled
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $active;


//      remplacé par l'attribut hérité de la classe parente : confirmationToken
//    /**
//     * @ORM\Column(type="string", length=32)
//     */
//    private $validationtoken;


//      doublon fonctionnel avec l'attribut hérité confirmationtoken
//    /**
//     * @ORM\Column(type="string", length=32)
//     */
//    private $recoverytoken;

//      remplacé par l'attribut hérité de la classe mère : password_requested_at
//    /**
//     * @ORM\Column(type="datetime")
//     */
//    private $recoverytokendate;


    /**
     * @ORM\Column(type="datetime")
     */
    private $creationdate;

    // cet attribut devra etre remplacé par l'utilisation correcte des groupes et roles

    /**
     *  @ORM\Column(type="smallint")
     */


    private $rights;
    //Les droits sont gérés comme les droits de fichier unix:
    //1 pour utilisateur normal,
    //2 pour admin,
    //4 pour administrateur de comptes
    //addition pour cumuler les droits
    //pour lire les droits, l'opération doit etre la suivant :
    //(rights/n)%2, où n désigne la valeur nominale du droit
    //n est forcément une puissance positive de 2

    private $city;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", cascade={"merge"}, fetch="LAZY")
     */
    private $address;

    /**
     * User constructor.
     */
    public function __construct()
    {   parent::__construct();
        $this->name=null;
        $this->firstname=null;
        $this->password=null;
        $this->id=null;
        $this->email=null;
        $this->banned=false;
        $this->rights=1;
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
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
    public function setId($id)
    {
        $this->id = $id;
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
    public function setBanned($banned)
    {
        $this->banned = $banned;
    }
    /**
     * @return mixed
     */

    /**
     * @return int
     */
    public function getRights()
    {
        return $this->rights;
    }
    /**
     * @param int $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
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
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
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
    public function setPhotopath(string $photopath)
    {
        $this->photopath = $photopath;
    }

    /**
     * @return mixed
     */
    public function getChatbanned()
    {
        return $this->chatbanned;
    }

    /**
     * @param mixed $chatbanned
     */
    public function setChatbanned($chatbanned)
    {
        $this->chatbanned = $chatbanned;
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
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
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
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

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



    public function rightsString()
    {   $str="right:";
        if($this->rights%2==1 )
        {   $str.="/user";
        }
        if(($this->rights/2)%2==1 )
        {   $str.="/admin";
        }
        if(($this->rights/4)%2==1 )
        {   $str.="/accAdmin";
        }
        return $str;
    }
}