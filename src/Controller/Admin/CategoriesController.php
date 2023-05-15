<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoryFormType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = new Categories();

        // On crée le formulaire
        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        // On traite la requête du formulaire
        $categoryForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            // On récupère les images

            // On génère le slug
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);
            $category->setParent($category->getId());

            // On stocke
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/add.html.twig',[
            'categoriesForm' => $categoryForm->createView()
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Categories $categorie, EntityManagerInterface $em): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $categorie);

        if (!$categorie) {
            // Si le produit n'existe pas, on retourne une erreur 404
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        // On supprime le produit
        $em->remove($categorie);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès');

        return $this->redirectToRoute('admin_categories_index');
    }
}
