<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use App\Service\PriceTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PromotionController extends AbstractController
{
    #[Route('/promotion/{id}-{slug}', name:'app_promotion', methods: ['GET'])]
    public function index(Promotion $promotion, string $slug, PromotionRepository $promotions,ProductRepository $productRepository, PriceTypeService $priceTypeService): Response {
        if (!in_array($promotion, $promotions->findActivePromotions()) || $promotion->getSlug() !== $slug) {
            throw $this->createNotFoundException('La page que vous cherchez n\'a pas été trouvée, peut-être n\'existe-t-elle plus.');
        }
        return $this->render('promotion/index.html.twig',[
            'promotion' => $promotion,
            'products' => $priceTypeService->getAllCurrentPrices($productRepository->findActiveByPromotion($promotion)),
        ]);
    }
}