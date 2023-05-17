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

        $promotion = new Promotions();
        $promotionForm = $this->createForm(PromotionsFormType::class, $promotion);

        // On traite la requête du formulaire
        $promotionForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if ($promotionForm->isSubmitted() && $promotionForm->isValid()) {
            // On génère le slug
            $slug = $slugger->slug($promotion->getPourcentage());
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

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Promotions $promotion, EntityManagerInterface $em): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $promotion);

        if (!$promotion) {
            // Si le produit n'existe pas, on retourne une erreur 404
            throw $this->createNotFoundException('La promotion n\'existe pas');
        }

        // On supprime le produit
        $em->remove($promotion);
        $em->flush();

        $this->addFlash('success', 'Promotion supprimée avec succès');

        return $this->redirectToRoute('admin_promotions_index');
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Promotions $promotion,Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On crée le formulaire
        $promotionForm = $this->createForm(PromotionsFormType::class, $promotion);

        // On traite la requête du formulaire
        $promotionForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if ($promotionForm->isSubmitted() && $promotionForm->isValid()) {
            // var_dump($promotion);die;
            // On génère le slug
            $slug = $slugger->slug($promotion->getPourcentage());
            $promotion->setSlug($slug);

            // On stocke
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', 'Promotion modifiée avec succès');

            // On redirige
            return $this->redirectToRoute('admin_promotions_index');
        }

        return $this->render('admin/promotions/edit.html.twig',[
            'promotionsForm' => $promotionForm->createView()
        ]);
    }
}