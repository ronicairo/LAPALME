<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/ajouter-un-commentaire', name: 'add_commentaire')]
    public function addCommentaire(Request $request, CommentaireRepository $repository): Response
    {
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireFormType::class, $commentaire)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $commentaire->setCreatedAt(new DateTime());
            $commentaire->setUpdatedAt(new DateTime());

            $repository->save($commentaire, true);

            $message= "Votre commentaire a bien été envoyé";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('show_home');
        }

        return $this->render('commentaire/form_commentaire.html.twig', [
            'form' => $form->createView()
        ]);
    } // end addCommentaire()

    #[Route('/supprimer-un-commentaire/{id}', name: 'hard_delete_commentaire', methods: ['GET'])]
    public function hardDeleteCommentaire(Commentaire $commentaire, CommentaireRepository $repository): Response
    {
        $repository->remove($commentaire, true);

        $this->addFlash('success', "Le commentaire a bien été supprimé.");

        return $this->redirectToRoute('show_home');
    } // end hardDeleteCommentaire()

}
