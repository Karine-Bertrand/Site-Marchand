<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Controller\SubmitType;
use App\Repository\ContactRepository;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{

    /**
     * @Route("/gestion", name="contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository)
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll()
        ]);
    }

    /**
     * @Route("/", name="contact_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
        
            $contact->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            // $notification->notify($contact);
            
            $emailTemplate = new TemplatedEmail();
            
            $emailTemplate->from(new Address('admin@lamainverte.com', 'La Main Verte'))
                    ->to($contact->getEmail())
                    ->subject('Demande de renseignements')
                    ->htmlTemplate('email/contact.html.twig');
        
            
            $this->addFlash('success','Votre message à bien été envoyé'); 
            
            
            return $this->redirectToRoute('contact_show', [
                'id' => $contact->getId()
                ]);
            
        }
    
        return $this->render('contact/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    /**
     * @Route("/{id}", name="contact_show", methods={"GET"})
     */
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contact_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index');
    }
}
