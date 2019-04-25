<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;
class SaleProductContentTest extends TestCase
{

    public function test__construct()
    {   $content=new SaleProductContent();
        $this->assertNull($content->getId());
        $this->assertNull($content->getProduct());
        $this->assertNull($content->getSale());
        $this->assertNull($content->getQuantity());
        $this->assertNull($content->getPricewhenbought());
    }

    public function testSetId()
    {   $content=new SaleProductContent();
        $content->setId(666);
        $this->assertEquals($content->getId(),666);

    }

    public function testSetSale()
    {   $content=new SaleProductContent();
        $sale=new Sale();
        $content->setSale($sale);
        $this->assertEquals($content->getSale(),$sale);

    }

    public function testSetQuantity()
    {   $content=new SaleProductContent();
        $content->setQuantity(666);
        $this->assertEquals($content->getQuantity(),666);
        try{
            $content->setQuantity(-1);
            $this->fail("expected UNexpectedValueException in setQuantity()");
        }catch (\UnexpectedValueException $e)
        {

        }

    }

    public function testSetPricewhenbought()
    {   $content=new SaleProductContent();
        $content->setPricewhenbought(707);
        $this->assertEquals($content->getPricewhenbought(),707);

    }

    public function testSetProduct()
    {   $content=new SaleProductContent();
        $product=new Product();
        $content->setProduct($product);
        $this->assertEquals($content->getProduct(),$product);

    }
}
