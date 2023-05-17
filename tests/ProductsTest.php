<?php

namespace App\Tests;

use App\Entity\Products;
use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $product = new Products();

        $product->setName('Test product');
        $this->assertEquals('Test product', $product->getName());

        $product->setDescription('This is a test product');
        $this->assertEquals('This is a test product', $product->getDescription());

        $product->setPrice(10);
        $this->assertEquals(10, $product->getPrice());

        $product->setStock(10);
        $this->assertEquals(10, $product->getStock());

        $category = new Categories();
        $product->setCategories($category);
        $this->assertEquals($category, $product->getCategories());
    }
}