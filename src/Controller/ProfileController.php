<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $encodedPassword = $passwordHasher->hashPassword($user, $data['newPassword']);

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
            'change_password_form' => $form->createView(),
        ]);
    }
}