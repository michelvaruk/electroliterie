<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', []);
    }

    #[Route('/ajouter/{id}', name:'app_cart_add', methods: ['GET'])]
    public function add(Product $product, Request $request, CartService $cartService): Response
    {
        $storedCart = json_decode($request->cookies->get('cart'), true);
        if(null == $storedCart)
            $storedCart = [];
        $cart = $cartService->add($storedCart, strval($product->getId()));
        $cookie = Cookie::create(
            'cart',
            json_encode($cart),
            secure: 'auto',
            httpOnly: true);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send(false);

        if ($request->query->get('redirect')) {
            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('success', 'Le produit ' . $product->getTitle() . ' a été ajouté à votre panier.');
        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/enlever/{id}', name:'app_cart_remove', methods: ['GET'])]
    public function remove(Product $product, Request $request, CartService $cartService): Response
    {
        $storedCart = json_decode($request->cookies->get('cart'), true);
        if(null == $storedCart)
            $storedCart = [];
        $cart = $cartService->remove($storedCart, strval($product->getId()));
        $cookie = Cookie::create(
            'cart',
            json_encode($cart),
            secure: true,
            httpOnly: false);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send(false);
        
        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_cart_delete_product', methods: ['POST'])]
    public function delete(Product $product, Request $request, CartService $cartService): Response
    {
        $storedCart = json_decode($request->cookies->get('cart'), true);
        if(null == $storedCart)
            $storedCart = [];
        $cart = $cartService->deleteProduct($storedCart, $product->getId());
        $cookie = Cookie::create(
            'cart',
            json_encode($cart),
            secure: true,
            httpOnly: false);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send(false);

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
