<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegistroUsuarioType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

class RegistroController extends AbstractController
{
    private $passwordEncoder;
    private $verifyEmailHelper;
    private $mailer;
    public function __construct(UserPasswordHasherInterface $passwordEncoder, VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }
    #[Route('/registro', name: 'app_registro')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(RegistroUsuarioType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $usuario->getEmail()]);

            if ($existingUser) {
                $this->addFlash('danger', 'El correo electrónico ya está en uso.');
                return $this->redirectToRoute('app_registro');
            }
            $usuario->setContrasenia($this->passwordEncoder->hashPassword(
                $usuario,
                $form->get('contrasenia')->getData()
            ));
            // Genera la firma del correo electrónico de verificación
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email', // Ruta que manejará la verificación del correo electrónico
                $usuario->getId(),
                $usuario->getEmail()
            );

            // Crea el correo electrónico de verificación
            $email = (new TemplatedEmail())
                ->from(new Address('k3vin.m.ramirez@gmail.com', 'Tu App'))
                ->to($usuario->getEmail())
                ->subject('Por favor confirma tu correo electrónico')
                ->htmlTemplate('registro/confirmation_email.html.twig')
                ->context([
                    'signedUrl' => $signatureComponents->getSignedUrl(),
                    'expiresAt' => $signatureComponents->getExpiresAt(),
                    'usuario' => $usuario,
                ]);

            $this->mailer->send($email);
            $entityManager->persist($usuario);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('registro/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
