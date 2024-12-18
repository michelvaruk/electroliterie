<?php

namespace App\Controller\Edit;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('edition/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_edit_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_edit_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/new.html.twig', [
            'brand' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'app_edit_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/switch', name:'app_edit_service_switch', methods: ['POST'])]
    public function switch(Service $service, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('switch' . $service->getId(), $request->getPayload()->get('_token'))) {
            if ($service->isActive()) {
                $service->setActive(false);
                $this->addFlash('success', 'Service ' . $service->getTitle() . ' désactivé.');
            } else {
                $service->setActive(true);
                $this->addFlash('success', 'Service ' . $service->getTitle() . ' activé.');
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_edit_service_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_edit_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_edit_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
