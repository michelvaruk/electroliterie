<?php

namespace App\Controller\Edit;

use App\Entity\Family;
use App\Form\FamilyType;
use App\Repository\FamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

#[Route('edition/famille')]
class FamilyController extends AbstractController
{
    #[Route('/', name: 'app_edit_family_index', methods: ['GET'])]
    public function index(FamilyRepository $familyRepository): Response
    {
        return $this->render('admin/family/index.html.twig', [
            'families' => $familyRepository->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_edit_family_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CacheInterface $cache): Response
    {
        $family = new Family();
        $form = $this->createForm(FamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($family);
            $entityManager->flush();

            $cache->delete('families');

            return $this->redirectToRoute('app_edit_family_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/family/new.html.twig', [
            'family' => $family,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_family_show', methods: ['GET'])]
    public function show(Family $family): Response
    {
        return $this->render('admin/family/show.html.twig', [
            'family' => $family,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_family_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Family $family, EntityManagerInterface $entityManager, CacheInterface $cache): Response
    {
        $form = $this->createForm(FamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $cache->delete('families');
            $this->addFlash('success', 'La famille ' . $family->getTitle() . ' a été modifiée.');
            return $this->redirectToRoute('app_edit_family_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/family/edit.html.twig', [
            'family' => $family,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_family_delete', methods: ['POST'])]
    public function delete(Request $request, Family $family, EntityManagerInterface $entityManager, CacheInterface $cache): Response
    {
        if ($this->isCsrfTokenValid('delete'.$family->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($family);
            $entityManager->flush();
            $cache->delete('families');
        }

        return $this->redirectToRoute('app_edit_family_index', [], Response::HTTP_SEE_OTHER);
    }
}
