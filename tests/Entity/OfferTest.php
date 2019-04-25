<?php

namespace App\Entity;
use PHPUnit\Framework\TestCase;

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
        $this->assertNull($offer->getPrice());
        $this->assertNull($offer->getDiscount());
        $this->assertNull($offer->getName());
        $this->assertNull($offer->getDescription());
        $this->assertNull($offer->getStart());
        $this->assertNull($offer->getEnd());
        $this->assertTrue($offer->getActive());

    }

    public function testSetDiscount()
    {

    }

    public function testSetActive()
    {

    }

    public function testSetPrice()
    {

    }

    public function testSetDescription()
    {

    }

    public function testSetPhotopath()
    {

    }

    public function testSetStart()
    {

    }

    public function testSetName()
    {

    }

    public function testSetEnd()
    {

    }
}
