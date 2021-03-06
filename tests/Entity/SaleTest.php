<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use DateTime;
class SaleTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSetOffers()
    {   $offer=new Offer();
        $sale= new Sale();
        $offers=new ArrayCollection();
        $offers->add($offer);
        $sale->setOffers($offers);
        $this->assertEquals($sale->getOffers(),$offers);
    }

    /**
     * @throws \Exception
     */
    public function testRemove()
    {   $sale= new Sale();
        $product=new Product();
        $service=new Service();
        $offer=new Offer();
        $sale->add($product,1);
        $sale->add($service,1);
        $sale->add($offer,1);
        $sale->remove($product);
        $sale->remove($service);
        $sale->remove($offer);
        $this->assertTrue($sale->getOffers()->isEmpty());
        $this->assertTrue($sale->getServices()->isEmpty());
        $this->assertTrue($sale->getProducts()->isEmpty());

    }

    /**
     * @throws \Exception
     */
    public function testUpdateQuantity()
    {   $sale= new Sale();
        $product=new Product();
        $service=new Service();
        $offer=new Offer();
        $sale->add($product,1);
        $sale->add($service,1);
        $sale->add($offer,1);
        $sale->updateQuantity($product,2);
        $sale->updateQuantity($service,3);
        $sale->updateQuantity($offer,4);
        $this->assertEquals($sale->getOffers()->first()->getQuantity(),4);
        $this->assertEquals($sale->getServices()->first()->getQuantity(),3);
        $this->assertEquals($sale->getProducts()->first()->getQuantity(),2);

    }

    /**
     * @throws \Exception
     */
    public function test__construct()
    {
        $sale= new Sale();
        $this->assertTrue($sale->getOnlinepay());
        $this->assertFalse($sale->getPaid());
        $this->assertEquals($sale->getDiscount(),0);
        $this->assertNull($sale->getPerson());
        $this->assertNull($sale->getDate());
        $this->assertTrue($sale->getProducts()->isEmpty());
        $this->assertTrue($sale->getOffers()->isEmpty());
        $this->assertTrue($sale->getServices()->isEmpty());
        $this->assertFalse($sale->getValidated());
    }

    /**
     * @throws \Exception
     */
    public function testSetDiscount()
    {   $sale= new Sale();
        $sale->setPaid(true);
        $this->assertTrue($sale->getPaid());
        $sale->setPaid(false);
        $this->assertFalse($sale->getPaid());


    }

    /**
     * @throws \Exception
     */
    public function testSetDate()
    {   $sale= new Sale();
    $currenttime=new DateTime();
        $sale->setDate($currenttime);
        $this->assertEquals($sale->getDate(),$currenttime);
    }

    /**
     * @throws \Exception
     */
    public function testSetPaid()
    {   $sale= new Sale();
        $sale->setPaid(false);
        $this->assertFalse($sale->getPaid());
        $sale->setPaid(true);
        $this->assertTrue($sale->getPaid());

    }

    /**
     * @throws \Exception
     */
    public function testSetProducts()
    {   $product=new Product();
        $sale= new Sale();
        $products=new ArrayCollection();
        $products->add($product);
        $sale->setProducts($products);
        $this->assertEquals($sale->getProducts(),$products);

    }

    /**
     * @throws \Exception
     */
    public function testSetServices()
    {   $service=new Service();
        $sale= new Sale();
        $services=new ArrayCollection();
        $services->add($service);
        $sale->setServices($services);
        $this->assertEquals($sale->getServices(),$services);

    }

    /**
     * @throws \Exception
     */
    public function testAdd()
    {   $sale= new Sale();
        $product=new Product();
        $service=new Service();
        $offer=new Offer();
        $sale->add($product,1);
        $sale->add($service,1);
        $sale->add($offer,1);
        try{$sale->add($product,-1);
            $this->fail("expected UnexpectedValueException");
        }catch (\UnexpectedValueException $e)
        {}
        try{$sale->addProduct($product,-1);
            $this->fail("expected UnexpectedValueException");
        }catch (\UnexpectedValueException $e)
        {}
        try{$sale->addService($service,-1);
            $this->fail("expected UnexpectedValueException");
        }catch (\UnexpectedValueException $e)
        {}
        try{$sale->addOffer($offer,-1);
            $this->fail("expected UnexpectedValueException");
        }catch (\UnexpectedValueException $e)
        {}
        $this->assertFalse($sale->getOffers()->isEmpty());
        $this->assertFalse($sale->getServices()->isEmpty());
        $this->assertFalse($sale->getProducts()->isEmpty());

    }

    /**
     * @throws \Exception
     */
    public function testSetPerson()
    {   $sale= new Sale();
        $person=new Person();
        $sale->setPerson($person);
        $this->assertEquals($sale->getPerson(),$person);
    }

    /**
     * @throws \Exception
     */
    public function testSetOnlinepay()
    {   $sale= new Sale();
        $sale->setOnlinepay(false);
        $this->assertFalse($sale->getOnlinepay());
        $sale->setOnlinepay(true);
        $this->assertTrue($sale->getOnlinepay());

    }

    /**
     * @throws \Exception
     */
    public function testSetId()
    {   $sale= new Sale();
        $sale->setId(666);
        $this->assertEquals(666,$sale->getId());
    }

    /**
     * @throws \Exception
     */
    public function testSetValidated()
    {   $sale= new Sale();
        $sale->setValidated(false);
        $this->assertFalse($sale->getValidated());
        $sale->setValidated(true);
        $this->assertTrue($sale->getValidated());
    }
}
