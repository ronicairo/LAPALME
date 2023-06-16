<?php

namespace App\Controller;

use DateTime;
use App\Entity\Carousel;
use App\Form\CarouselFormType;
use App\Repository\CarouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CarouselController extends AbstractController
{
    #[Route('/ajouter-un-carousel', name: 'create_carousel', methods: ['GET', 'POST'])]
    public function createCarousel(CarouselRepository $repository, Request $request, SluggerInterface $slugger): Response
    {
        $carousel = new Carousel();

        $form = $this->createForm(CarouselFormType::class, $carousel)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $carousel->setCreatedAt(new DateTime());
            $carousel->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo & */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $carousel, $slugger);
            } //end if($photo)

            $repository->save($carousel, true);

            $this->addFlash('success', "Le carousel a bien été ajouté avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } // end if($form)

        return $this->render('admin/show_carousel.html.twig', [
            'form' => $form->createView(),
        ]);
    } // end createCarousel()


    #[Route('/carousel', name: 'show_carousel', methods: ['GET'])]
    public function showCarousel(EntityManagerInterface $entityManager): Response
    {

        $carousels = $entityManager->getRepository(Carousel::class)->findAll();

        return $this->render('admin/show_dashboard.html.twig', [
            'carousels' => $carousels,
        ]);
    }

    #[Route('/modifier-un-carousel{id}', name: 'update_carousel', methods: ['GET', 'POST'])]
    public function updateCarousel(Carousel $carousel, Request $request, CarouselRepository $repository, SluggerInterface $slugger): Response
    {
        $currentPhoto = $carousel->getPhoto();

        $form = $this->createForm(CarouselFormType::class, $carousel, [
            'photo' => $currentPhoto,
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $carousel->setCreatedAt(new DateTime());
            $carousel->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $carousel, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentPhoto);
            } else {
                $carousel->setPhoto($currentPhoto);
            } // end if($photo)

            $this->addFlash('success', "Le carousel a bien eté modifié avec succès !");
            return $this->redirectToRoute(('show_dashboard'));
        }
        return $this->render('admin/show_carousel.html.twig', [
            'form' => $form->createView(),
            'carousel' => $carousel

        ]);
    } // end updateCarousel()

    #[Route('archiver-un-carousel/{id}', name: 'soft_delete_carousel', methods: ['GET'])]
    public function softDeleteCarousel(Carousel $carousel, CarouselRepository $repository): Response
    {
        $carousel->setDeletedAt(new DateTime());

        $repository->save($carousel, true);

        $this->addFlash('success', "Le carousel a bien été archivé.");
        return $this->redirectToRoute('show_dashboard');
    } // end softDeleteCarousel

    #[Route('restaurer-un-carousel/{id}', name: 'restore_carousel', methods: ['GET'])]
    public function restoreCarousel(Carousel $carousel, CarouselRepository $repository): Response
    {
        $carousel->setDeletedAt(null);

        $repository->save($carousel, true);

        $this->addFlash('success', "Le carousel a bien été restauré");
        return $this->redirectToRoute(('show_archive'));
    } // end restoreCarousel

    #[Route('supprimer-un-carousel/{id}', name: 'hard_delete_carousel', methods: ['GET'])]
    public function hardDeleteCarousel(Carousel $carousel, CarouselRepository $repository): Response
    {
        $photo = $carousel->getPhoto();

        $repository->remove($carousel, true);

        unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $photo);

        $this->addFlash('success', "Le carousel a bien été supprimé définitivement !");
        return $this->redirectToRoute(('show_archive'));
    } // end hardDeletecarousel

    private function handleFile(UploadedFile $photo, Carousel $carousel, SluggerInterface $slugger)
    {
        $extension = "." . $photo->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $carousel->setPhoto($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', "Le fichier de la photo ne s'est pas importé correctement. Veuillez réessayer." . $exception->getMessage());
        } // end catch()
    } // end handleFile()
    
}
