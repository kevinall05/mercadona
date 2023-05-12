<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Repository\PromotionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository, PromotionsRepository $promotionsRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);
        $products = $productsRepository->findAll();
        $promotions = $promotionsRepository->findAll();


        return $this->render('main/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'promotions' => $promotions,
        ]);
    }
}
