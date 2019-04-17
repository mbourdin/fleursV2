<?php

namespace App\Entity;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{

    public function testSetName()
    {   $city=new City();
        $city->setName("cityname");
        $this->assertEquals("cityname",$city->getName());

    }

    public function testSetInseeid()
    {   $city=new City();
        $city->setInseeid(666);
        $this->assertEquals(666,$city->getInseeid());

    }

    public function testActiveString()
    {   $city=new City();
        $this->assertEquals($city->activeString(),"active");
        $city->setActive(false);
        $this->assertEquals($city->activeString(),"inactive");
    }

    public function testSetId()
    {   $city=new City();
        $city->setId(666);
        $this->assertEquals(666,$city->getId());

    }

    public function testSetActive()
    {   $city=new City();
        $city->setActive(false);
        $this->assertFalse($city->getActive());
    }

    public function test__construct()
    {   $city=new City();
        $this->assertNull($city->getId());
        $this->assertNull($city->getName());
        $this->assertNull($city->getInseeid());
        $this->assertTrue($city->getActive());


    }
}
