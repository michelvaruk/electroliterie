<?php

namespace App\Controller\Edit;

use App\Entity\DeliveryMode;
use App\Form\DeliveryModeType;
use App\Repository\DeliveryModeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edition/livraison')]
class DeliveryModeController extends AbstractController
{
    #[Route('/', name:'app_edit_delivery_mode_index')]
    public function index(DeliveryModeRepository $delivery): Response {
        return $this->render('admin/delivery_mode/index.html.twig', [
            'deliveries' => $delivery->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_edit_delivery_mode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deliveryMode = new DeliveryMode();
        $form = $this->createForm(DeliveryModeType::class, $deliveryMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($deliveryMode);
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_delivery_mode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/delivery_mode/new.html.twig', [
            'delivery' => $deliveryMode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_delivery_mode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DeliveryMode $deliveryMode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliveryModeType::class, $deliveryMode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le mlode de livraison ' . $deliveryMode->getTitle() . ' a été modifiée.');
            return $this->redirectToRoute('app_edit_delivery_mode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/delivery_mode/edit.html.twig', [
            'delivery' => $deliveryMode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/switch', name:'app_edit_delivery_mode_switch', methods: ['POST'])]
    public function switch(DeliveryMode $deliveryMode, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('switch' . $deliveryMode->getId(), $request->getPayload()->get('_token'))) {
            if ($deliveryMode->isActive()) {
                $deliveryMode->setActive(false);
                $this->addFlash('success', 'Mode de livraison ' . $deliveryMode->getTitle() . ' désactivé.');
            } else {
                $deliveryMode->setActive(true);
                $this->addFlash('success', 'Mode de livraison ' . $deliveryMode->getTitle() . ' activé.');
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_edit_delivery_mode_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_edit_delivery_mode_delete', methods: ['POST'])]
    public function delete(Request $request, DeliveryMode $deliveryMode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deliveryMode->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($deliveryMode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_edit_delivery_mode_index', [], Response::HTTP_SEE_OTHER);
    }
}