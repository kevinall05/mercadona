<?php

namespace App\Controller\Admin;

use App\Entity\Promotions;
use App\Form\PromotionsFormType;
use App\Repository\PromotionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/promotions', name: 'admin_promotions_')]
class PromotionsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PromotionsRepository $promotionsRepository): Response
    {
        $promotions = $promotionsRepository->findAll();
        return $this->render('admin/promotions/index.html.twig', compact('promotions'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On crée un "nouveau produit"
        $promotion = new Promotions();

        // On crée le formulaire
        $promotionForm = $this->createForm(PromotionsFormType::class, $promotion);

        // On traite la requête du formulaire
        $promotionForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if ($promotionForm->isSubmitted() && $promotionForm->isValid()) {
            // On génère le slug
            $slug = $slugger->slug($promotion->getId());
            $promotion->setSlug($slug);

            // On stocke
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', 'Promotion ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_promotions_index');
        }

        return $this->render('admin/promotions/add.html.twig',[
            'promotionsForm' => $promotionForm->createView()
        ]);
    }
}