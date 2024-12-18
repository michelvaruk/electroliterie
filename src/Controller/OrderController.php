<?php

namespace App\Controller;

use App\Entity\InvoiceLine;
use App\Entity\Order;
use App\Form\OrderType;
use App\Form\OrderValidationType;
use App\Repository\OrderRepository;
use App\Service\DistanceService;
use App\Service\OrderService;
use App\Twig\Extension\ClientExtension;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[IsGranted('ROLE_CUSTOMER')]
#[Route('/commande')]
class OrderController extends AbstractController
{
    #[Route('/nouvelle', name:'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OrderRepository $orderRepository, ClientExtension $clientExtension): Response {
        $cart = json_decode($request->cookies->get('cart'), true);
        if($cart == null || count($cart) == 0) {
            $this->addFlash('alert', 'Votre panier est vide, vous ne pouvez pas le valider.');
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        $order = new Order();
        $order->setCustomer($this->getUser());
        $latestOrder = $orderRepository->findOneBy(['customer' => $this->getUser()], ['id' => 'DESC']);
        if($latestOrder)
            $latestOrderInvoiceAdress = $latestOrder->getInvoiceAdress();

        $form = $this->createForm(OrderType::class, $order,[
            'is_new' => false,]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $order->setCreatedAt(new DateTimeImmutable());
            foreach($clientExtension->getCart() as $item) {
                $invoiceLine = new InvoiceLine;
                $invoiceLine
                    ->setProduct($item['product'])
                    ->setQuantity($item['qty'])
                    ->setTaxFreeUnitPrice($item['product']->getCurrentPrice() / 1.2)
                    ->setTaxFreeDeee($item['product']->getSalesInfo()->getEcoContribution() / 1.2)
                    ->setVatRate(20)
                ;
                $order->addInvoiceLine($invoiceLine);
            }
            $order
                ->setOrderStatus('ORDER_CREATED');
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Votre panier a été vidé, votre commande est désormais disponible dans la section Mon profil > Mes commandes.');

            $response = new Response();
            $response->headers->clearCookie('cart');
            $response->send(false);

            return $this->redirectToRoute('app_order_validate', ['id' => $order->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
            'customer' => $this->getUser(),
            'order' => $order,
            'form' => $form,
            'latestInvoiceAdress' => $latestOrderInvoiceAdress ?? null,
        ]);
    }

    #[Route('/{id}/validation', name:'app_order_validate', methods: ['GET', 'POST'])]
    public function validate(Order $order, Request $request, EntityManagerInterface $entityManager, OrderService $orderService) : Response {
        if($order->getCustomer() !== $this->getUser())
            throw $this->createNotFoundException('La page que vous cherchez n\'existe pas.');

        $distance = DistanceService::distance($order->getInvoiceAdress()->getLat(), $order->getInvoiceAdress()->getLongitude());

        $form = $this->createForm(OrderValidationType::class, $order, ['distance' => $distance]);
        $form->handleRequest($request);

        $total = $orderService->getTotal($order);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($order->getInvoiceLines() as $invoiceLine) {
                if ($invoiceLine->getProduct()->getSalesInfo()->getStock() < 1) {
                    $this->addFlash('alert', 'Pour valider votre commande, merci de nous contacter avec le numéro de commande ' . $order->getId());
                    return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
                }
            }
            $order
                ->setDeliveryTaxFreePrice(DistanceService::computePrice($order->getDeliveryMode(), $distance) / 1.20)
                ->setOrderStatus('ORDER_COMPLETE');
                
            $entityManager->flush();

            $redirect = $this->createCharge($order);
            $session = $request->getSession();
            $session->set('redirect', $redirect);
            return $this->redirectToRoute('app_checkout', ['id' => $order->getId()]);
        }

        return $this->render('order/validate.html.twig', [
            'order' => $order,
            'form' => $form,
            'customer' => $this->getUser(),
            'totalPrice' => $total['totalTtc'],
            'totalDeee' => $total['totalDeee'],
        ]);
    }

    public function createCharge(Order $order):string {
        $lineItems = [];
        foreach($order->getInvoiceLines() as $invoiceLine) {
            $lineItem = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $invoiceLine->getProduct()->getTitle(),
                        'tax_code' => 'txcd_99999999',
                    ],
                    'unit_amount' => $invoiceLine->getTaxFreeUnitPrice() * 100
                ],
                'quantity' => $invoiceLine->getQuantity(),
            ];
            $lineItems[] = $lineItem;
        }
        $delivery = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getDeliveryMode()->getTitle(),
                    'tax_code' => 'txcd_99999999'
                ],
                'unit_amount' => $order->getDeliveryTaxFreePrice() * 100
            ],
            'quantity' => 1,
        ];
        $lineItems[] = $delivery;
        Stripe\Stripe::setApiKey($this->getParameter('stripe.secret_key'));
        $checkout_session = Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_checkout', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . '?stripe=true',
            'client_reference_id' => $order->getId(),
            'automatic_tax' => [
                'enabled' => false,
            ],
            'customer_email' => $order->getCustomer()->getEmail(),
        ]);

        return $checkout_session->url;
    }
}