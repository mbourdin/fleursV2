<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;
class MessageAdminTest extends TestCase
{

    public function test__construct()
    {   $msg=new MessageAdmin();
        $this->assertNull($msg->getId());
        $this->assertNull($msg->getEmail());
        $this->assertNull($msg->getText());
        $this->assertFalse($msg->getIsread());

    }

    public function testSetEmail()
    {   $msg=new MessageAdmin();
        $msg->setEmail("toto");
        $this->assertEquals($msg->getEmail(),"toto");
    }

    public function testSetIsread()
    {   $msg=new MessageAdmin();
        $msg->setIsread(true);
        $this->assertTrue($msg->getIsread());
        $msg->setIsread(false);
        $this->assertFalse($msg->getIsread());

    }

    public function testSetText()
    {   $msg=new MessageAdmin();
        $msg->setText("quand le ciel bas et lour pèse comme un couvercle sur l'esprit gémissant en proie aux longs ennuis");
        $this->assertEquals($msg->getText(),"quand le ciel bas et lour pèse comme un couvercle sur l'esprit gémissant en proie aux longs ennuis");

    }

    public function testSetId()
    {   $msg=new MessageAdmin();
        $msg->setId(644);
        $this->assertEquals($msg->getId(),644);

    }
}
