<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\PriceTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;


class ProductController extends AbstractController
{
    #[Route('/produit/{id}-{slug}', name: 'app_product', methods: ['GET'])]
    public function show(Product $product, string $slug, PriceTypeService $priceTypeService): Response
    {
        // Véifier que le slug correspond à l'id et que le produit est actif
        if ($product->getSlug() != $slug || !$product->isActive())
            throw $this->createNotFoundException('La page que vous cherchez n\'a pas été trouvée, peut-être n\'existe-t-elle plus.');

        return $this->render('product/show.html.twig', [
            'product' => $priceTypeService->getCurrentPrice($product)
        ]);
    }

    #[Route('/api/product/search', name: 'app_product_search', methods: ['POST'])]
    public function productSearch(
        Request $request,
        ProductRepository $productRepository,
        SerializerInterface $serializer,
        PriceTypeService $priceService): Response
    {
        $stringFromStimulus = json_decode($request->getContent(), true);

        $products = $priceService->getAllCurrentPrices($productRepository->findBySearchString($stringFromStimulus));

        $json = $serializer->serialize($products, 'json', ['groups' => 'product:read']);

        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products/search/results/list', name: 'app_search_results', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $productIds = json_decode($request->getContent(), true);

        return $this->redirectToRoute('app_product_results_page', [
            'ids' => implode(',', $productIds)
        ]);
    }

    #[Route('/products/results/{ids}', name: 'app_product_results_page')]
    public function productResultsPage(ProductRepository $productRepository, string $ids, PriceTypeService $priceTypeService): Response
    {
        $productIds = explode(',', $ids);
        $products = $productRepository->findBy(['id' => $productIds]);

        return $this->render('promotion/index.html.twig', [
            'products' => $priceTypeService->getAllCurrentPrices($products)
        ]);
    }
}
