<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reglement')]
class CheckoutController extends AbstractController
{
    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('/{id}', name: 'app_checkout', methods:['GET'])]
    public function process(Order $order, Request $request, OrderService $orderService): Response {
        if($request->query->get('stripe'))
            $this->addFlash('alert', 'Votre règlement n\'a pas pu être enregistré, merci de ré-essayer.');
        $total = $orderService->getTotal($order);

        return $this->render('checkout/process.html.twig', [
            'redirect' => $request->getSession()->get('redirect'),
            'order' => $order,
            'totalPrice' => $total['totalTtc'],
            'totalDeee' => $total['totalDeee'],
        ]);
    }

    #[Route('/success', name:'app_stripe_success', methods: ['GET'])]
    public function displaySuccess(): Response {
        $this->addFlash('success', 'Votre règlement a bien été enregistré, votre commande est en attente de validation par notre équipe.');
        return $this->redirectToRoute('app_main');
    }
}