<?php

namespace App\Tests;

use App\Entity\Users;
use App\Entity\Products;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $user = new Users();

        $user->setEmail('kallemand@ham05.com');
        $this->assertEquals('kallemand@ham05.com', $user->getEmail());

        $user->setPassword('stud!');
        $this->assertEquals('stud!',$user->getPassword());

        $user->setLastname('ALLEMAND');
        $this->assertEquals('ALLEMAND',$user->getLastname());

        $user->setFirstname('Kevin');
        $this->assertEquals('Kevin',$user->getFirstname());

        $user->setAddress('33 CHEMIN DES VIGNEAUX');
        $this->assertEquals('33 CHEMIN DES VIGNEAUX',$user->getAddress());

        $user->setZipcode('05000');
        $this->assertEquals('05000',$user->getZipcode());

        $user->setCity('GAP');
        $this->assertEquals('GAP',$user->getCity());
    }
}