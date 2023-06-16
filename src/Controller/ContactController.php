<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'show_contact')]
    public function showContact(Request $request, ContactRepository $repository): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $contact->setCreatedAt(new DateTime());
        $contact->setUpdatedAt(new DateTime());

            $repository->save($contact, true);

            $this->addFlash('success', "Votre message a bien été envoyé !");
            return $this->redirectToRoute('show_home');
        } // end if($form)

        return $this->render('restaurant/contact_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/message', name: 'show_message', methods: ['GET'])]
    public function showMessage(EntityManagerInterface $entityManager): Response
    {

        $messages = $entityManager->getRepository(Contact::class)->findAll();

        return $this->redirectToRoute('show_dashboard', [
            'messages' => $messages,
        ]);
    }

    #[Route('/archiver-un-message/{id}', name: 'soft_delete_message', methods: ['GET'])]
    public function softDeleteMessage(Contact $contact, ContactRepository $repository): Response
    {
        $contact->setDeletedAt(new DateTime());

        $repository->save($contact, true);

        $this->addFlash('success', "Le message a bien été archivé.");
        return $this->redirectToRoute('show_dashboard');
    } // end softDeleteMessage

    #[Route('restaurer-un-message{id}', name: 'restore_message', methods: ['GET'])]
    public function restoreMessage(Contact $contact, ContactRepository $repository): Response
    {
        $contact->setDeletedAt(null);

        $repository->save($contact, true);

        $this->addFlash('success', "Le message a bien été restauré");
        return $this->redirectToRoute(('show_archive'));
    } // end restoreMessage

    #[Route('supprimer-un-message/{id}', name: 'hard_delete_message', methods: ['GET'])]
    public function hardDeleteMessage(Contact $contact, ContactRepository $repository): Response
    {
        $repository->remove($contact, true);
        
        $this->addFlash('success', "Le message a bien été supprimé définitivement !");
        return $this->redirectToRoute(('show_archive'));
    } // end hardDeleteMessage
}