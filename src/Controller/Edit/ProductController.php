<?php

namespace App\Controller\Edit;

use App\Entity\Product;
use App\Form\FamilySelectType;
use App\Form\ProductType;
use App\Repository\FamilyRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edition/produit')]
class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private FamilyRepository $families)
    {
    }

    #[Route('/', name: 'app_edit_product_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
            'families' => $this->families->findAll(),
        ]);
    }

    #[Route('/famille', name: 'app_edit_product_family', methods: ['GET', 'POST'])]
    public function newFamily(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(FamilySelectType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_edit_product_new', ['family' => $form->get('familyName')->getNormData()->getTitle()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/creer/{family}', name:'app_edit_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $family, EntityManagerInterface $em, FamilyRepository $familyRepository): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product, ['family' => $family]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setFamilyName($familyRepository->findOneBy(['title' => $family]));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_edit_product_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/product/new.html.twig', [
                'product' => $product,
                'form' => $form,
            ]);
    }

    #[Route('/{id}', name: 'app_edit_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product, ['family' => $product->getFamilyName()->getTitle()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/switch', name:'app_edit_product_switch', methods: ['POST'])]
    public function switch(Product $product, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('switch' . $product->getId(), $request->getPayload()->get('_token'))) {
            $col = $request->getPayload()->get('type');
            switch($col) {
                case 'active':
                    $getter = 'isActive';
                    $setter = 'setActive';
                    break;
                case 'star':
                    $getter = 'isSelection';
                    $setter = 'setSelection';
                    break;
            }
            if ($product->$getter()) {
                $product->$setter(false);
                $this->addFlash('success', 'Produit ' . $product->getTitle() . ' désactivé.');
            } else {
                $product->$setter(true);
                $this->addFlash('success', 'Produit ' . $product->getTitle() . ' activé.');
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_edit_product_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_edit_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            if(count($product->getInvoiceLines()) > 0) {
                $this->addFlash('alert', 'Ce produit ayant été vendu, il ne peut être supprimé');
            } else {
                $entityManager->remove($product);
                $entityManager->flush();
                $this->addFlash('success', 'Le produit '. $product->getTitle() .' a été supprimé');
            }
        }

        return $this->redirectToRoute('app_edit_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
