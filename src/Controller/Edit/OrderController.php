<?php

namespace App\Controller\Edit;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/edition/commande')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_edit_order_index', methods: ['GET'])]
    public function index(OrderRepository $order):Response {
        return $this->render('admin/order/index.html.twig', [
            'orders' => $order->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    #[Route('/{id}', name: 'app_edit_order_show', methods: ['GET'])]
    public function show(Order $order, OrderService $orderService): Response {
        $total = $orderService->getTotal($order);
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
            'total' => $total,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_edit_order_index', [], Response::HTTP_SEE_OTHER);
    }
}