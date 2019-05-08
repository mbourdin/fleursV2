<?php

namespace App\Entity;


use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testSetDeleted()
    {   $person =new Person();
        $person->setDeleted(true);
        $this->assertTrue($person->getDeleted());


    }

    public function testSetName()
    {   $person =new Person();
        $person->setName("DTC");
        $this->assertEquals($person->getName(),"DTC");

    }

    public function testSetCreationdate()
    {   $person =new Person();
        $person->setCreationdate(new \DateTime());
        $this->assertTrue($person->getCreationdate() instanceof \DateTime);
    }

    public function testSetRights()
    {   $person =new Person();
        $person->setRights(5);
        $this->assertEquals($person->getRights(),5);

    }

    public function testSetFirstname()
    {   $person =new Person();
        $person->setFirstName("DTC");
        $this->assertEquals($person->getFirstName(),"DTC");
    }

    public function testSetBirthday()
    {   $person = new Person();
        $person ->setBirthday(new \DateTime());
        $this->assertTrue($person->getBirthday() instanceof \DateTime);

    }

    public function testSetPhotopath()
    {   $person = new Person();
        $person->setPhotopath("/photo/path");
        $this->assertEquals("/photo/path",$person->getPhotopath());
    }

    public function testSetBanned()
    {   $person = new Person();
        $person->setBanned(true);
        $this->assertTrue($person->getBanned());
        $person->setBanned(false);
        $this->assertFalse($person->getBanned());
    }

    public function testSetAddress()
    {   //todo Faire la classe adresse avant de reecrire ce test
        $this->assertTrue(true);
    }

    public function testSetId()
    {    $person = new Person();
        $person->setId(99);
        $this->assertEquals($person->getId(),99);

    }
    public function test__Construct()
    {   $person=new Person();
        $this->assertEquals($person->getRights(),1);
    }
}
