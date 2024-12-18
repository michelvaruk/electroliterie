<?php

namespace App\Controller\Edit;

use App\Entity\ProductPictures;
use App\Form\ProductPicturesType;
use App\Repository\ProductPicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edtition/product/pictures')]
class ProductPicturesController extends AbstractController
{
    #[Route('/', name: 'app_edit_product_pictures_index', methods: ['GET'])]
    public function index(ProductPicturesRepository $productPicturesRepository): Response
    {
        return $this->render('admin/product_pictures/index.html.twig', [
            'product_pictures' => $productPicturesRepository->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_edit_product_pictures_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productPicture = new ProductPictures();
        $form = $this->createForm(ProductPicturesType::class, $productPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($productPicture);
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_product_pictures_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product_pictures/new.html.twig', [
            'product_picture' => $productPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_product_pictures_show', methods: ['GET'])]
    public function show(ProductPictures $productPicture): Response
    {
        return $this->render('admin/product_pictures/show.html.twig', [
            'product_picture' => $productPicture,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_product_pictures_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductPictures $productPicture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductPicturesType::class, $productPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_product_pictures_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product_pictures/edit.html.twig', [
            'product_picture' => $productPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_product_pictures_delete', methods: ['POST'])]
    public function delete(Request $request, ProductPictures $productPicture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $productPicture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($productPicture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_edit_product_pictures_index', [], Response::HTTP_SEE_OTHER);
    }
}
