<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use App\Service\PriceTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name:'app_main', methods: ['GET'])]
    public function index(AboutRepository $about, PromotionRepository $promotions, PriceTypeService $priceTypeService, ProductRepository $productRepository, Request $request): Response {
        if($request->query->get('product'))
            $this->addFlash(
                'success',
                'Le produit ' . $productRepository->find($request->query->get('product'))->getTitle() . ' a bien été ajouté au panier.');
        return $this->render('main/index.html.twig', [
            'about' => $about->find(1),
            'promotions' => $promotions->findActivePromotions(),
            'products' => $priceTypeService->getAllCurrentPrices($productRepository->findBy(['active' => true, 'selection' => true], [],10))
        ]);
    }
}