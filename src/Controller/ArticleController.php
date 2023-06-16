<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticleController extends AbstractController
{
    #[Route('/ajouter-un-article', name: 'create_article', methods: ['GET', 'POST'])]
    public function createArticle(ArticleRepository $repository, Request $request, SluggerInterface $slugger): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setCreatedAt(new DateTime());
            $article->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $article, $slugger);
            } //end if($photo)

            $repository->save($article, true);

            $this->addFlash('success', "L'article a bien été crée avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } // end if($form)

        return $this->render('article/form.article.html.twig', [
            'form' => $form->createView()
        ]);
    } // end createArticle()


    #[Route('/modifier-un-article{id}', name: 'update_article', methods: ['GET', 'POST'])]
    public function updateArticle(Article $article, Request $request, ArticleRepository $repository, SluggerInterface $slugger): Response
    {
        $currentPhoto = $article->getPhoto();

        $form = $this->createForm(ArticleFormType::class, $article, [
            'photo' => $currentPhoto
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setCreatedAt(new DateTime());
            $article->setUpdatedAt(new DateTime());
    
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $article, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentPhoto);
            }
            else {
                $article->setPhoto($currentPhoto);
            } // end if($photo)

            $repository->save($article, true);

            $this->addFlash('success', "L'article a bien eté modifié avec succès !");
            return $this->redirectToRoute(('show_dashboard'));
        }
        return $this->render('article/form.article.html.twig', [
            'form' => $form->createView(),
            'article' => $article

        ]);
    } // end updateArticle()

    #[Route('archiver-un-article/{id}', name: 'soft_delete_article', methods: ['GET'])]
    public function softDeleteArticle(Article $article, ArticleRepository $repository): Response
    {
        $article->setDeletedAt(new DateTime());

        $repository->save($article, true);

        $this->addFlash('success', "L'article a bien été archivée.");
        return $this->redirectToRoute('show_dashboard');

    } // end softDeleteArticle

    #[Route('restaurer-un-article/{id}', name: 'restore_article', methods: ['GET'])]
    public function restoreArticle(Article $article, ArticleRepository $repository): Response
    {
        $article->setDeletedAt(null);

        $repository->save($article, true);

        $this->addFlash('success', "L'article a bien été restauré");
        return $this->redirectToRoute(('show_archive'));
    } // end restoreArticle

    #[Route('supprimer-un-article/{id}', name: 'hard_delete_article', methods: ['GET'])]
    public function hardDeleteArticle(Article $article, ArticleRepository $repository): Response
    {
        $photo = $article->getPhoto();

        $repository->remove($article, true);

        unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $photo);
        
        $this->addFlash('success', "L'article a bien été supprimé définitivement !");
        return $this->redirectToRoute(('show_archive'));
    } // end hardDeleteArticle
    
    private function handleFile(UploadedFile $photo, Article $article, SluggerInterface $slugger)
    {
        $extension = "." . $photo->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $article->setPhoto($newFilename);
        } catch (FileException $exception) {
            // code a exécuter en cas d'erreur
        }
    } // end handleFile()
    
}
