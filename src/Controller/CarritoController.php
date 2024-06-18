<?php

// src/Controller/CarritoController.php

namespace App\Controller;

use App\Entity\Carrito;
use App\Entity\DetallesPedido;
use App\Entity\Pedidos;
use App\Entity\Plato;
use App\Repository\PedidosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class CarritoController extends AbstractController
{
    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(
        Request $request,
        EntityManagerInterface $entityManager,
        PedidosRepository $pedidoRepository,
        UserInterface $usuario
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        $platoId = $request->request->get('platoId');
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
        $carrito = $entityManager->getRepository(Carrito::class)->findOneBy(['pedido' => $pedido, 'plato' => $plato]);
        if ($carrito) {
            $carrito->setCantidad($carrito->getCantidad() + $cantidad);
            $carrito->setPrecioTotal($carrito->getCantidad() * $plato->getPrecio());
        } else {
            $carrito = new Carrito();
            $carrito->setPlato($plato);
            $carrito->setCantidad($cantidad);
            $carrito->setPrecioTotal($plato->getPrecio() * $cantidad);
            $carrito->setUsuario($usuario);
            $carrito->setPedido($pedido);
            $entityManager->persist($carrito);
        }
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
        $total = 0;
        foreach ($carritoItems as $item) {
            $total += $item->getPlato()->getPrecio() * $item->getCantidad();
        }
        return $this->render('carrito.html.twig', [
            'carritoItems' => $carritoItems,
            'total' => $total,
        ]);
    }

    #[Route('/confirmar-pedido', name: 'confirmar_pedido', methods: ['POST'])]
    public function confirmarPedido(
        PedidosRepository $pedidoRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UserInterface $user,
        Request $request
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return new Response(json_encode(['error' => 'Unauthorized']), Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'application/json']);
        }

        $pedido = $pedidoRepository->findOneBy(['usuario' => $user, 'status' => 'pending']);

        if (!$pedido) {
            return new Response(json_encode(['error' => 'No hay pedido pendiente']), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $pedido->setStatus('completed');
        $totalPrecio = 0;
        foreach ($pedido->getCarritos() as $detalle) {
            $totalPrecio += $detalle->getPrecioTotal();
        }
        $pedido->setTotalPrecio($totalPrecio);
        $entityManager->flush();

        $detalles = [];
        foreach ($pedido->getCarritos() as $detalle) {
            $detalles[] = [
                'plato' => $detalle->getPlato(),
                'cantidad' => $detalle->getCantidad(),
            ];
            $entityManager->remove($detalle);
        }
        $entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('k3vin.m.ramirez@gmail.com')
            ->to($user->getUserIdentifier())
            ->subject('Detalles del Pedido')
            ->htmlTemplate('detalles_pedido.html.twig')
            ->context([
            'usuario' => $user,
            'pedido' => $pedido,
            'detalles' => $detalles,
            'totalPrecio' => $pedido->getTotalPrecio()]);

        try {
            $mailer->send($email);
        } catch (\Exception $e) {
            return new Response(json_encode(['error' => 'Error al enviar el correo: ' . $e->getMessage()]), Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
        }
        $session = $request->getSession();
        $session->remove('cart');
        return new Response(json_encode(['success' => true, 'message' => 'Pedido confirmado y correo enviado']), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
