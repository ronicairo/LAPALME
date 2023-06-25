<?php

namespace App\Controller;

use DateTime;
use App\Entity\Galerie;
use App\Form\GalerieFormType;
use App\Repository\GalerieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GalerieController extends AbstractController
{
    #[Route('/ajouter-une-galerie', name: 'create_galerie', methods: ['GET', 'POST'])]
    public function createGalerie(GalerieRepository $repository, Request $request, SluggerInterface $slugger): Response
    {

        $galerie = new Galerie();

        $form = $this->createForm(GalerieFormType::class, $galerie)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $galerie->setCreatedAt(new DateTime());
            $galerie->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $img = $form->get('img')->getData();

            if ($img) {
                $this->handleFile($img, $galerie, $slugger);
            } //end if($img)

            $repository->save($galerie, true);

            $this->addFlash('success', "L'image a bien été ajouté à la galerie !");
            return $this->redirectToRoute('show_dashboard');
        } // end if($form)

        return $this->render('admin/show_galerie.html.twig', [
            'form' => $form->createView()
        ]);
    } // end createGalerie()

    #[Route('/modifier-une-galerie{id}', name: 'update_galerie', methods: ['GET', 'POST'])]
    public function updateGalerie(Galerie $galerie, Request $request, GalerieRepository $repository, SluggerInterface $slugger): Response
    {
        $currentImg = $galerie->getImg();

        $form = $this->createForm(GalerieFormType::class, $galerie, [
            'img' => $currentImg,
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $galerie->setCreatedAt(new DateTime());
            $galerie->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $img = $form->get('img')->getData();

            if ($img) {
                $this->handleFile($img, $galerie, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentImg);
            } else {
                $galerie->setImg($currentImg);
            } // end if($img)
        
            $repository->save($galerie, true);

            $this->addFlash('success', "L'image a été modifié avec succès !");
            return $this->redirectToRoute(('show_dashboard'));
        }
        return $this->render('admin/show_galerie.html.twig', [
            'form' => $form->createView(),
            'galerie' => $galerie

        ]);
    } // end updateGalerie()

    #[Route('supprimer-une-galerie/{id}', name: 'hard_delete_galerie', methods: ['GET'])]
    public function hardDeleteGalerie(Galerie $galerie, GalerieRepository $repository): Response
    {
        $img = $galerie->getImg();

        $repository->remove($galerie, true);

        unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $img);
       
        $this->addFlash('success', "La galerie a bien été supprimé définitivement !");
        return $this->redirectToRoute(('show_archive'));
    } // end hardDeleteGalerie

    private function handleFile(UploadedFile $img, Galerie $galerie, SluggerInterface $slugger)
    {
        $extension = "." . $img->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME));

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $img->move($this->getParameter('uploads_dir'), $newFilename);
            $galerie->setImg($newFilename);
        } catch (FileException $exception) {
            // code a exécuter en cas d'erreur
        }
    } // end handleFile()

}
