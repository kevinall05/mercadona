<?php

namespace App\Tests;

use App\Entity\Products;
use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $category = new Categories();

        $category->setName('Test categorie');
        $this->assertEquals('Test categorie', $category->getName());

        $category->setCategoryOrder(10);
        $this->assertEquals(10, $category->getCategoryOrder());

        $category->setParent($category);
        $this->assertEquals($category, $category->getParent());

        $product = new Products();
        $category->addProduct($product);
        $this->assertEquals($product, $category->getProducts());
    }
}