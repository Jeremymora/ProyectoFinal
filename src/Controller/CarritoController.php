<?php

// src/Controller/CarritoController.php

namespace App\Controller;

use App\Entity\Carrito;
use App\Entity\DetallesPedido;
use App\Entity\Pedidos;
use App\Entity\Plato;
use App\Repository\PedidosRepository;
use App\Repository\PlatoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class CarritoController extends AbstractController
{
    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(
        Request $request,
        EntityManagerInterface $entityManager,
        PlatoRepository $platoRepository,
        PedidosRepository $pedidoRepository,
        UserInterface $usuario
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $platoId = $request->request->get('platoId');
        dump($platoId);
        if (!$platoId) {
            return new Response('ID de plato no válido', Response::HTTP_BAD_REQUEST);
        }
        $cantidad = $request->request->get('cantidad', 1);

        $plato = $entityManager->getRepository(Plato::class)->find($platoId);
        if (!$plato) {
            return new Response('Plato no encontrado', Response::HTTP_NOT_FOUND);
        }

        $pedido = $pedidoRepository->findOneBy(['usuario' => $usuario, 'status' => 'pending']);
        if (!$pedido) {
            $pedido = new Pedidos();
            $pedido->setUsuario($usuario);
            $pedido->setFechaDelPedido(new \DateTime());
            $pedido->setTotalPrecio(0);
            $entityManager->persist($pedido);
            $entityManager->flush();
        }

        $carrito = new Carrito();
        $carrito->setPlato($plato);
        $carrito->setCantidad($cantidad);
        $carrito->setPrecioTotal($plato->getPrecio() * $cantidad);
        $carrito->setUsuario($usuario);
        $carrito->setPedido($pedido);
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

        return $this->render('carrito.html.twig', [
            'carritoItems' => $carritoItems,
        ]);
    }

    #[Route('/confirmar-pedido', name: 'confirmar_pedido', methods: ['POST'])]
    public function confirmarPedido(
        PedidosRepository $pedidoRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UserInterface $user
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $pedido = $pedidoRepository->findOneBy(['usuario' => $user, 'status' => 'pending']);

        if (!$pedido) {
            return new Response('No hay pedido pendiente', Response::HTTP_BAD_REQUEST);
        }

        $pedido->setStatus('completed');
        $entityManager->flush();

        $detalles = '';
        foreach ($pedido->getDetallesPedidos() as $detalle) {
            $detalles .= $detalle->getPlato()->getNombreDelPlato() . ' - ' . $detalle->getCantidad() . ' x ' . $detalle->getPlato()->getPrecio() . "\n";
        }

        $email = (new TemplatedEmail())
            ->from('k3vin.m.ramirez@gmail.com')
            ->to($user->getUserIdentifier())
            ->subject('Detalles del Pedido')
            ->htmlTemplate('detalles_pedido.html.twig')
            ->context(['usuario' => $user,'detalles' => $detalles, 'totalPrecio' => $pedido->getTotalPrecio()]);

        $mailer->send($email);

        return new Response(json_encode(['success' => true, 'message' => 'Pedido confirmado y correo enviado']), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
