<?php

namespace App\Controller\Edit;

use App\Entity\About;
use App\Form\AboutType;
use App\Repository\AboutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edition/a-propos')]
class AboutController extends AbstractController
{
    #[Route('/', name: 'app_edit_about_index', methods: ['GET'])]
    public function index(AboutRepository $aboutRepository) : Response {
        return $this->render('admin/about/index.html.twig', [
            'about' => $aboutRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_edit_about_edit', methods: ['GET', 'POST'], condition: "params['id'] < 4")]
    public function edit(Request $request, About $about, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AboutType::class, $about, ['id' => $about->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_about_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/about/edit.html.twig', [
            'about' => $about,
            'form' => $form,
        ]);
    }
}
