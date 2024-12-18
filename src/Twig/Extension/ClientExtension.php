<?php

namespace App\Twig\Extension;

use App\Repository\FamilyRepository;
use App\Repository\ProductRepository;
use App\Repository\ProfessionRepository;
use App\Service\PriceTypeService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;


class ClientExtension extends AbstractExtension
{
    public function __construct(
        private FamilyRepository $family,
        private ProfessionRepository $profession,
        private ProductRepository $productRepository,
        private CacheInterface $cache,
        private RequestStack $request,
        private PriceTypeService $priceTypeService)
    {
        
    }

    public function findFamilies()
    {
        return $this->cache->get('families', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return $this->family->findAll();
        });
    }

    public function findProfessions()
    {
        return $this->cache->get('professions', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return $this->profession->findBy(['active' => true]);
        });
    }

    public function getCart()
    {
        $cart = json_decode($this->request->getCurrentRequest()->cookies->get('cart'), true);
        if(!$cart) {
            $products = [];
        } else {
            foreach($cart as $id => $qty) {
                $products[] = [
                    'product' => $this->priceTypeService->getCurrentPrice($this->productRepository->find($id)),
                    'qty' => $qty
                ];
            }
        }
        return $products;
    }
}
