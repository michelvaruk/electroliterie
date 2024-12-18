<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/nos-services', name:'app_services', methods: ['GET'])]
    public function index(ServiceRepository $service) : Response {
        return $this->render('service/index.html.twig', [
            'services' => $service->findBy(['active' => true]),
        ]);
    }
}