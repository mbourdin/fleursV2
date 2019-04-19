<?php

namespace App\Entity;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use PHPUnit\Framework\TestCase;
class SaleOfferContentTest extends TestCase
{

    public function test__construct()
    {   $offercontent = new SaleOfferContent();
        $this->assertNull($offercontent->getOffer());
        $this->assertNull($offercontent->getQuantity());
        $this->assertNull($offercontent->getSale());

    }

    public function testSetSale()
    {   $offercontent = new SaleOfferContent();
        $sale=new Sale();
        $offercontent->setSale($sale);
        $this->assertEquals($sale,$offercontent->getSale());

    }

    public function testSetOffer()
    {   $offercontent = new SaleOfferContent();
        $offer=new Offer();
        $offercontent->setOffer($offer);
        $this->assertEquals($offer,$offercontent->getOffer());

    }

    public function testSetQuantity()
    {   $offercontent = new SaleOfferContent();
        $offercontent->setQuantity(1);
        $this->assertEquals($offercontent->getQuantity(),1);
        try{
            $offercontent->setQuantity(-1);
            $this->fail("expected UnexpectedValueException");
        }catch (UnexpectedValueException $e){}
    }
}
