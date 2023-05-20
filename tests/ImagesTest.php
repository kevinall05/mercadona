<?php

namespace App\Tests;

use App\Entity\Images;
use App\Entity\Products;
use PHPUnit\Framework\TestCase;

class ImagesTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $image = new Images();

        $image->setName('Test image');
        $this->assertEquals('Test image', $image->getName());

        $product = new Products();
        $image->setProducts($product);
        $this->assertEquals($product, $image->getProducts());
    }
}