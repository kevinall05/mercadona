<?php

namespace App\Tests;

use App\Entity\Promotions;
use App\Entity\Products;
use PHPUnit\Framework\TestCase;
use DateTime;

class PromotionsTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $promotion = new Promotions();

        $product = new Products();
        $promotion->setProducts($product);
        $this->assertEquals($product, $promotion->getProducts());

        $promotion->setPourcentage(10);
        $this->assertEquals(10,$promotion->getPourcentage());

        $dateTime = new DateTime("2023-05-20");
        $promotion->setDateDeb($dateTime);
        $this->assertEquals($dateTime,$promotion->getDateDeb());

        $dateTime = new DateTime("2023-05-20");
        $promotion->setDateFin($dateTime);
        $this->assertEquals($dateTime,$promotion->getDateFin());
    }
}