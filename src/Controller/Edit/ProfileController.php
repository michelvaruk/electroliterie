<?php

namespace App\Controller\Edit;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/edition/mon-compte')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_edit_account_show', methods: ['GET'])]
    public function show(#[CurrentUser()] User $user) : Response {
        return $this->render('admin/account/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/modifier', name: 'app_edit_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[CurrentUser()] User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute('app_edit_account_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}