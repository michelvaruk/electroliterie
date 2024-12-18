<?php

namespace App\Controller\Edit;

use App\Entity\Profession;
use App\Form\ProfessionType;
use App\Repository\ProfessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edition/metier')]
class ProfessionController extends AbstractController
{
    #[Route('/', name: 'app_edit_profession_index', methods: ['GET'])]
    public function index(ProfessionRepository $professionRepository): Response
    {
        return $this->render('admin/profession/index.html.twig', [
            'professions' => $professionRepository->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_edit_profession_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profession = new Profession();
        $form = $this->createForm(ProfessionType::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profession);
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_profession_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/profession/new.html.twig', [
            'brand' => $profession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_profession_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Profession $profession, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfessionType::class, $profession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_profession_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/profession/edit.html.twig', [
            'profession' => $profession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/switch', name:'app_edit_profession_switch', methods: ['POST'])]
    public function switch(Profession $profession, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('switch' . $profession->getId(), $request->getPayload()->get('_token'))) {
            if ($profession->isActive()) {
                $profession->setActive(false);
                $this->addFlash('success', 'Métier ' . $profession->getTitle() . ' désactivé.');
            } else {
                $profession->setActive(true);
                $this->addFlash('success', 'Métier ' . $profession->getTitle() . ' activé.');
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_edit_profession_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_edit_profession_delete', methods: ['POST'])]
    public function delete(Request $request, Profession $profession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $profession->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($profession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_edit_profession_index', [], Response::HTTP_SEE_OTHER);
    }
}
