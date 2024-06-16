<?php

// src/Controller/CarritoController.php

namespace App\Controller;

use App\Entity\Carrito;
use App\Entity\DetallesPedido;
use App\Entity\Pedidos;
use App\Entity\Plato;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CarritoController extends AbstractController
{
    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $platoId = $request->request->get('platoId');
        $cantidad = $request->request->get('cantidad', 1);

        $plato = $entityManager->getRepository(Plato::class)->find($platoId);
        if (!$plato) {
            return new Response('Plato no encontrado', Response::HTTP_NOT_FOUND);
        }

        $carrito = new Carrito();
        $carrito->setUsuario($user);
        $carrito->setPlato($plato);
        $carrito->setCantidad($cantidad);
        $carrito->setPrecioTotal($plato->getPrecio() * $cantidad);

        $entityManager->persist($carrito);
        $entityManager->flush();

        return new Response(json_encode(['success' => true]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/carrito', name: 'ver_carrito', methods: ['GET'])]
    public function verCarrito(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $carritoItems = $entityManager->getRepository(Carrito::class)->findBy(['usuario' => $user]);

        return $this->render('carrito/ver_carrito.html.twig', [
            'carritoItems' => $carritoItems,
        ]);
    }

    #[Route('/confirmar-pedido', name: 'confirmar_pedido', methods: ['POST'])]
    public function confirmarPedido(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $carritoItems = $entityManager->getRepository(Carrito::class)->findBy(['usuario' => $user]);

        if (empty($carritoItems)) {
            return new Response('El carrito está vacío', Response::HTTP_BAD_REQUEST);
        }

        $pedido = new Pedidos();
        $pedido->setUsuario($user);
        $pedido->setFechaDelPedido(new \DateTime());

        $totalPrecio = 0;
        foreach ($carritoItems as $item) {
            $detallesPedido = new DetallesPedido();
            $detallesPedido->setPedido($pedido);
            $detallesPedido->setPlato($item->getPlato());
            $detallesPedido->setCantidad($item->getCantidad());
            $detallesPedido->setPrecioTotal($item->getPrecioTotal());

            $entityManager->persist($detallesPedido);
            $totalPrecio += $item->getPrecioTotal();

            $entityManager->remove($item);
        }

        $pedido->setTotalPrecio($totalPrecio);

        $entityManager->persist($pedido);
        $entityManager->flush();

        return new Response(json_encode(['success' => true, 'pedidoId' => $pedido->getId()]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
