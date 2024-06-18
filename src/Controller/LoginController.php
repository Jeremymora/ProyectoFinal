<?php

// src/Controller/LoginController.php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error instanceof CustomUserMessageAuthenticationException) {
            return $this->render('login/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        }
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $lastUsername]);
        if ($usuario && !$usuario->getIsVerified()) {
            // Usuario no verificado, mostrar un mensaje de error y redirigir
            $error = new CustomUserMessageAuthenticationException('Tu cuenta aún no ha sido verificada. Por favor, verifica tu correo electrónico.');
            return $this->render('login/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        }
        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('logout', name: 'app_logout')]
    public function logout()
    {
        return;
    }
}
