<?php

namespace App\Controller;

use App\Entity\Detail;
use App\Form\DetailType;
use App\Repository\DetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/detail")
 */
class DetailController extends AbstractController
{
    /**
     * @Route("/", name="detail_index", methods={"GET"})
     */
    public function index(DetailRepository $detailRepository): Response
    {
        return $this->render('detail/index.html.twig', [
            'details' => $detailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="detail_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $detail = new Detail();
        $form = $this->createForm(DetailType::class, $detail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($detail);
            $entityManager->flush();

            return $this->redirectToRoute('detail_index');
        }

        return $this->render('detail/new.html.twig', [
            'detail' => $detail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="detail_show", methods={"GET"})
     */
    public function show(Detail $detail): Response
    {
        return $this->render('detail/show.html.twig', [
            'detail' => $detail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="detail_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Detail $detail): Response
    {
        $form = $this->createForm(DetailType::class, $detail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('detail_index');
        }

        return $this->render('detail/edit.html.twig', [
            'detail' => $detail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="detail_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Detail $detail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($detail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('detail_index');
    }
}
