<?php

namespace App\Controller;

use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Bridge\Leaflet\LeafletOptions;
use Symfony\UX\Map\Bridge\Leaflet\Option\TileLayer;

class AboutController extends AbstractController
{
    #[Route('/a-propos', name:'app_about', methods: ['GET'])]
    public function index(AboutRepository $about):Response {
         // 1. Create a new map instance
        $map = (new Map())
         // Explicitly set the center and zoom
        ->center(new Point($this->getParameter('app.must_latitude'), $this->getParameter('app.must_longitude')))
        ->zoom(12);
         // Or automatically fit the bounds to the markers
        // ->fitBoundsToMarkers();

        // 2. You can add markers
        $map
            ->addMarker(new Marker(
                position: new Point($this->getParameter('app.must_latitude'), $this->getParameter('app.must_longitude')), 
                title: 'Must Ménager Anglet',
                infoWindow: new InfoWindow(
                    headerContent: '<b>Must Ménager Anglet</b>',
                    content: 'Électroménager, literie, arts de la table'
                )
            ));
        $leafletOptions = (new LeafletOptions())
            ->tileLayer(new TileLayer(
            url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            options: [
                'minZoom' => 8,
                'maxZoom' => 18,
            ],
        ));

        $map->options($leafletOptions);


        return $this->render('about/index.html.twig', [
            'about' => $about->find(1),
            'delivery' => $about->find(2),
            'waranty' => $about->find(3),
            'map' => $map
        ]);
    } 
}