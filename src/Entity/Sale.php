<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleRepository")
 */
class Sale
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onlinepay;
    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;
    /**
     * @ORM\Column(type="boolean")
     */
    private $discount;
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", fetch="LAZY")
     */
    private $person;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleProductContent",mappedBy="sale",cascade={"persist","merge","remove"},fetch="LAZY")
     * @ORM\JoinTable(name="sale_product_content")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleOfferContent",mappedBy="sale",cascade={"persist","merge","remove"},fetch="LAZY")
     * @ORM\JoinTable(name="sale_offer_content")
     */
    private $offers;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleServiceContent",mappedBy="sale",cascade={"persist","merge","remove"},fetch="LAZY")
     * @ORM\JoinTable(name="sale_service_content")
     */
    private $services;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;
    /**
     * @ORM\Column(type="string")
     */
    private $recipient;
    /**
     * @ORM\Column(type="string")
     */
    private $contact;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", fetch="LAZY",cascade={"persist"})
     */
    private $address;

    private $priceTmp;
    public function getPriceTmp()
    {
        return $this->priceTmp;
    }

    /**
     * Sale constructor.
     */
    public function __construct()
    {
        $this->onlinepay = true;
        $this->paid = false;
        $this->discount = 0;
        $this->person = null;
        $this->products = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->validated=false;
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
    public function setId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOnlinepay()
    {
        return $this->onlinepay;
    }

    /**
     * @param mixed $onlinepay
     */
    public function setOnlinepay(bool $onlinepay)
    {
        $this->onlinepay = $onlinepay;
    }

    /**
     * @return mixed
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * @param mixed $paid
     */
    public function setPaid(bool $paid)
    {
        $this->paid = $paid;
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
    public function setDiscount(int $discount)
    {   if ($discount<0) throw new \RuntimeException;
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
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
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @param mixed $offers
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param mixed $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }

    //ajoute des services, des produits ou des offres à la commande
    //cette fonction fait appel aux 3 sous-fonctions ci-dessous
    //renvoie l'objet ajouté
    /**
     * @param $object
     * @param $quantity
     * @return SaleOfferContent|SaleProductContent|SaleServiceContent|null|mixed l'objet contenu ajouté
     */
    public function add($object, $quantity)
    {
        if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");
        if ($object instanceof Product) {
            $result = $this->addProduct($object, $quantity);
        } elseif ($object instanceof Service) {
            $result = $this->addService($object, $quantity);
        } elseif ($object instanceof Offer) {
            $result = $this->addOffer($object, $quantity);
        } else {
            $result = null;
        }
        return $result;
    }

//ajoute $quantity de $product au panier, renvoie l'objet contenu ajouté

    /**
     * @param Product $product
     * @param int $quantity
     * @return SaleProductContent|mixed l'objet contenu ajouté
     */
    public function addProduct(Product $product, int $quantity)
    {
        if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");
        foreach ($this->products->getIterator() as $i => $productContent) {
            if ($product->equals($productContent->getProduct())) {

                $oldQuantity = $productContent->getQuantity();
                $newQuantity = $oldQuantity + $quantity;
                $productContent->setQuantity($newQuantity);
                return $productContent;
            }
        }
        $newProduct = new SaleProductContent();
        $newProduct->setProduct($product);
        $newProduct->setSale($this);
        $newProduct->setQuantity($quantity);
        $newProduct->setPricewhenbought($product->getPrice());
        $this->products->add($newProduct);

        return $newProduct;
    }

//ajoute $quantity de $service au panier, renvoie l'objet contenu ajouté

    /**
     * @param Service $service
     * @param int $quantity
     * @return SaleServiceContent|mixed l'objet contenu ajouté
     */
    public function addService(Service $service, int $quantity)
    {

        if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");
        foreach ($this->services->getIterator() as $i => $serviceContent) {
            if ($service->equals($serviceContent->getService())) {

                $oldQuantity = $serviceContent->getQuantity();
                $newQuantity = $oldQuantity + $quantity;
                $serviceContent->setQuantity($newQuantity);
                return $serviceContent;
            }
        }
        $newService = new SaleServiceContent();
        $newService->setService($service);
        $newService->setSale($this);
        $newService->setQuantity($quantity);
        $newService->setPricewhenbought($service->getPrice());
        $this->services->add($newService);

        return $newService;
    }

    //ajoute $quantity de $offre au panier , renvoie l'objet contenu ajouté

    /**
     * @param Offer $offer
     * @param int $quantity
     * @return SaleOfferContent|mixed l'objet contenu ajouté
     */
    public function addOffer(Offer $offer, int $quantity)
    {
        if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");
        foreach ($this->offers->getIterator() as $i => $offerContent) {
            if ($offer->equals($offerContent->getOffer())) {

                $oldQuantity = $offerContent->getQuantity();
                $newQuantity = $oldQuantity + $quantity;
                $offerContent->setQuantity($newQuantity);
                return $offerContent;
            }
        }
        $newOffer = new SaleOfferContent();
        $newOffer->setOffer($offer);
        $newOffer->setSale($this);
        $newOffer->setQuantity($quantity);
        $newOffer->setPricewhenbought($offer->price());
        $this->offers->add($newOffer);


        return $newOffer;
    }
    //supprime un produit/service /une offre du panier
    //cette fonction appelle les 3 fonctions ci-dessous
    // revoie l'objet contenu effacé
    /**
     * @param $object
     * @return mixed|null l'objet contenu effacé
     */
    public function remove($object)
    {
        if ($object instanceof Product) {
            return $this->removeProduct($object);
        } elseif ($object instanceof Service) {
            return $this->removeService($object);
        } elseif ($object instanceof Offer) {
            return $this->removeOffer($object);
        }
        return null;
    }

    //supprime un produit du panier

    /**
     * @param Product $product
     * @return mixed|null  l'objet contenu effacé
     */
    public function removeProduct(Product $product)
    {
        foreach ($this->products->getIterator() as $i => $productContent) {
            if ($product->equals($productContent->getProduct())) {
                $this->products->removeElement($productContent);
                return $productContent;
            }
        }
        return null;
    }

    // supprime un service du panier

    /**
     * @param Service $service
     * @return mixed|null l'objet contenu effacé
     */
    public function removeService(Service $service)
    {
        foreach ($this->services->getIterator() as $i => $serviceContent) {
            if ($service->equals($serviceContent->getService())) {
                $this->services->removeElement($serviceContent);
                return $serviceContent;
            }
        }
        return null;
    }

    // supprime une offre du panier

    /**
     * @param Offer $offer
     * @return mixed|null l'objet contenu effacé
     */
    public function removeOffer(Offer $offer)
    {
        foreach ($this->offers->getIterator() as $i => $offerContent) {
            if ($offer->equals($offerContent->getOffer())) {
                $this->offers->removeElement($offerContent);
                return $offerContent;
            }
        }
        return null;
    }
    // met à jour la quantité de l'objet dans le panier
    //supprime l'objet si quantité négative ou nulle
    //renvoie l'objet mis a jour
    /**
     * @param $object
     * @param int $quantity
     * @return bool|mixed|null l'objet contenu mis à jour
     */
    public function updateQuantity($object, int $quantity)
    {
        if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");

        if ($object instanceof Product) {
            return $this->updateProductQuantity($object, $quantity);
        } elseif ($object instanceof Service) {
            return $this->updateServiceQuantity($object, $quantity);
        } elseif ($object instanceof Offer) {
            return $this->updateOfferQuantity($object, $quantity);
        } else {
            return false;
        }
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return mixed|null l'objet contenu mis à jour
     */
    public function updateProductQuantity(Product $product,int $quantity)
    {   if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");

        foreach ($this->products->getIterator() as $i => $productContent) {
            if ($product->equals($productContent->getProduct())) {
                $productContent->setQuantity($quantity);
                return $productContent;
            }
        }
        return null;
    }

    /**
     * @param Service $service
     * @param int $quantity
     * @return mixed|null l'objet contenu mis à jour
     */
    public function updateServiceQuantity(Service $service,int $quantity)
    {   if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");

        foreach ($this->services->getIterator() as $i => $serviceContent) {
            if ($service->equals($serviceContent->getService())) {
                $serviceContent->setQuantity($quantity);
                return $serviceContent;
            }
        }
        return null;
    }

    /**
     * @param Offer $offer
     * @param int $quantity
     * @return mixed|null l'objet contenu mis à jour
     */
    public function updateOfferQuantity(Offer $offer,int $quantity)
    {   if ($quantity <= 0) throw new \UnexpectedValueException("quantité négative");

        foreach ($this->offers->getIterator() as $i => $offerContent) {
            if ($offer->equals($offerContent->getOffer())) {
                $offerContent->setQuantity($quantity);
                return $offerContent;
            }
        }
        return null;
    }
    //renvie en texte le contenu du panier

    /**
     * @return string
     */
    public function toString()
    {
        $str = "id:" . $this->id
            . "/onlinepay:" . $this->onlinepay
            . "/paid:" . $this->paid
            . "/discount:" . $this->discount
            . "/date:" . $this->date->format(DATE_ATOM);
        foreach ($this->products->getIterator() as $i => $productContent) {
            $str .= $productContent->toString();
        }
        foreach ($this->offers->getIterator() as $i => $offerContent) {
            $str .= $offerContent->toString();
        }
        foreach ($this->services->getIterator() as $i => $serviceContent) {
            $str .= $serviceContent->toString();
        }
        return $str;
    }
    //calcule le prix total de la vente, le stocke dans l'attribut priceTMP et renvoie cette valeur
    /**
     * @return int prix total de la vente
     */
    public function price():int
    {
        $result= 0;


         foreach ($this->products->getIterator() as $i => $productContent) {
            $result +=$productContent->getPricewhenbought()*$productContent->getQuantity();
        }
        foreach ($this->services->getIterator() as $i => $serviceContent) {
            $result +=$serviceContent->getPricewhenbought()*$serviceContent->getQuantity();
        }
        foreach ($this->offers->getIterator() as $i => $offerContent) {
            $result +=$offerContent->getPricewhenbought()*$offerContent->getQuantity();
        }
        $this->priceTmp=$result;
        return $result;
    }

    /**
     * @return mixed
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * @param mixed $validated
     */
    public function setValidated($validated): void
    {
        $this->validated = $validated;
    }
    public function empty():bool
    {
        return ($this->products->isEmpty()
            && $this->offers->isEmpty()
            && $this->services->isEmpty()
        );
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient): void
    {
        $this->recipient = $recipient;
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
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact): void
    {
        $this->contact = $contact;
    }
}