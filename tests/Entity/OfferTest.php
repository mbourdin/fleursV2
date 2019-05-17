<?php

namespace App\Entity;
use PHPUnit\Framework\TestCase;
use DateTime;
class OfferTest extends TestCase
{

    public function testSetId()
    {   $offer=new Offer();
        $offer->setId(666);
        $this->assertEquals($offer->getId(),666);
    }

    public function test__construct()
    {   $offer=new Offer();
        $this->assertNull($offer->getId());
        $this->assertEquals($offer->price(),0);
        $this->assertNull($offer->getDiscount());
        $this->assertNull($offer->getName());
        $this->assertNull($offer->getDescription());
        $this->assertNull($offer->getStart());
        $this->assertNull($offer->getEnd());
        $this->assertTrue($offer->getActive());

    }

    public function testSetDiscount()
    {   $offer=new Offer();
        $offer->setDiscount(69);
        $this->assertEquals(69,$offer->getDiscount());

    }

    public function testSetActive()
    {   $offer=new Offer();
        $offer->setActive(false);
        $this->assertFalse($offer->getActive());
        $offer->setActive(true);
        $this->assertTrue($offer->getActive());
    }

    public function testSetDescription()
    {   $offer=new Offer();
        $offer->setDescription("Chaine de caracteres beaucoup trop longue");
        $this->assertEquals("Chaine de caracteres beaucoup trop longue",$offer->getDescription());

    }
    public function testSetStart()
    {   $offer=new Offer();
        $datetime=new DateTime();
        $offer->setStart($datetime);
        $this->assertEquals($offer->getStart(),$datetime);

    }

    public function testSetName()
    {   $offer=new Offer();
        $name="nomdoffre";
        $offer->setName($name);
        $this->assertEquals($offer->getName(),$name);

    }

    public function testSetEnd()
    {
        $offer=new Offer();
        $datetime=new DateTime();
        $offer->setEnd($datetime);
        $this->assertEquals($offer->getEnd(),$datetime);

    }
}
