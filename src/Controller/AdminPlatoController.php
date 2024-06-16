<?php

namespace App\Controller;

use App\Entity\Plato;
use App\Form\PlatoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/plato', name: 'admin_plato_')]
class AdminPlatoController extends AbstractController
{
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $plato = new Plato();
        $form = $this->createForm(PlatoType::class, $plato);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al insertar imagen: ' . $e->getMessage());
                }

                $plato->setImage($newFilename);
            }

            $entityManager->persist($plato);
            $entityManager->flush();

            return $this->redirectToRoute('admin_plato_list');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $platos = $entityManager->getRepository(Plato::class)->findAll();

        return $this->render('admin/borrarPlato.html.twig', [
            'platos' => $platos,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Plato $plato, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager->remove($plato);
        $entityManager->flush();

        return $this->redirectToRoute('admin_plato_list');
    }
}
