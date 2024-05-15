<?php

// src/Controller/VerificacionEmailController.php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerificacionEmailController extends AbstractController
{
    private $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    #[Route('/verificar/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id'); // Recupera el id del usuario desde la URL

        if (null === $id) {
            // Redirige al usuario a la página de registro si no hay id en la URL
            return $this->redirectToRoute('app_registro');
        }

        $usuario = $entityManager->getRepository(Usuario::class)->find($id);

        if (null === $usuario) {
            // Redirige al usuario a la página de registro si el usuario no existe
            return $this->redirectToRoute('app_registro');
        }

        // Valida la firma del correo electrónico y marca el usuario como verificado
        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, $usuario->getId(), $usuario->getEmail());

            $usuario->setIsVerified(true);
            $entityManager->persist($usuario);
            $entityManager->flush();

            $this->addFlash('success', 'Tu correo electrónico ha sido verificado.');

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_registro');
        }

        return $this->redirectToRoute('app_home');
    }
}
