<?php

namespace App\Controller;

use App\Entity\Family;
use App\Repository\ProductRepository;
use App\Service\FilterService;
use App\Service\PriceTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ReflectionClass;

class FamilyController extends AbstractController
{
    #[Route('/famille/{id}-{slug}', name: 'app_family', methods: ['GET'])]
    public function index(
        Family $family,
        string $slug,
        PriceTypeService $priceTypeService,
        ProductRepository $productRepository): Response
    {
        if ($family->getSlug() !== $slug) {
            throw $this->createNotFoundException('La page que vous cherchez n\'a pas été trouvée, peut-être n\'existe-t-elle plus.');
        }

        $products = $priceTypeService->getAllCurrentPrices(
            $productRepository->findBy(['active' => true, 'familyName' => $family])
        );

        $features = $family->getFeatures();
        $features[] = 'price';
        $features[] = 'brand';
        $filters = [];

        foreach ($features as $feature) {
            $result = FilterService::$feature($products);
            foreach ($result as $filter => $items) {
                $filters[$filter] = $items;
            }
        }

        return $this->render('family/index.html.twig', [
            'family' => $family,
            'products' => $products,
            'filters' => $filters,
        ]);
    }
}
