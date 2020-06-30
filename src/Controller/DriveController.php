<?php

namespace App\Controller;

use App\Entity\Drive;
use App\Form\DriveType;
use App\Repository\DriveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/drive")
 */
class DriveController extends AbstractController
{
    /**
     * @Route("/gestion", name="drive_index", methods={"GET", "POST"})
     */
    public function index(DriveRepository $driveRepository, Request $request): Response
    {
        $drive = new Drive();
        $form = $this->createForm(DriveType::class, $drive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($drive);
            $entityManager->flush();

            return $this->redirectToRoute('drive_index');
        }

        return $this->render('drive/index.html.twig', [
            'drives' => $driveRepository->findAllWithAddress(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="drive_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $drive = new Drive();
        $form = $this->createForm(DriveType::class, $drive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drive->addCompany($this->getUser()->getCompany());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($drive);
            $entityManager->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('drive/new.html.twig', [
            'drive' => $drive,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drive_show", methods={"GET"})
     */
    public function show(Drive $drive): Response
    {
        return $this->render('drive/show.html.twig', [
            'drive' => $drive,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="drive_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Drive $drive): Response
    {
        $form = $this->createForm(DriveType::class, $drive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('drive/edit.html.twig', [
            'drive' => $drive,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drive_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Drive $drive): Response
    {
        if ($this->isCsrfTokenValid('delete'.$drive->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($drive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profil_index');
    }
}
