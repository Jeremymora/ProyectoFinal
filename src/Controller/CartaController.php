<?php

// src/Controller/CartaController.php

namespace App\Controller;

use App\Entity\Plato;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CartaController extends AbstractController
{
    #[Route('/carta', name: 'carta_list')]
    public function index()
    {
        return $this->render('Products.html.twig');
    }
    #[Route('/api/platos', name: 'api_platos', methods: ['GET'])]
    public function getPlatos(EntityManagerInterface $em): JsonResponse
    {
        $platos = $em->getRepository(Plato::class)->findAll();
        $data = [];

        foreach ($platos as $plato) {
            $data[] = [
                'id' => $plato->getId(),
                'nombreDelPlato' => $plato->getNombreDelPlato(),
                'peso' => $plato->getPeso(),
                'precio' => $plato->getPrecio(),
                'kcal' => $plato->getKcal(),
                'disponibilidad' => $plato->getDisponibilidad(),
                'image' => $plato->getImage(),
            ];
        }

        return new JsonResponse($data);
    }
}
