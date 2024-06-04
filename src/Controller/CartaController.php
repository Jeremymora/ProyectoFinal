<?php

// src/Controller/CartaController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartaController extends AbstractController
{
    #[Route('/carta', name: 'carta_list')]
    public function index(): Response
    {
        return $this->render('/Products.html.twig');
    }

    #[Route('products.json', name: 'carta_json')]
    public function getProducts(): JsonResponse
    {
        $jsonPath = $this->getParameter('kernel.project_dir') . '/public/json/products.json';
        $data = json_decode(file_get_contents($jsonPath), true);

        return new JsonResponse($data);
    }
}
