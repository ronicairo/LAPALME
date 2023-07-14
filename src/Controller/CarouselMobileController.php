<?php

namespace App\Controller;

use DateTime;
use App\Entity\Carouselmobile;
use App\Form\CarouselMobileFormType;
use App\Repository\CarouselmobileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CarouselMobileController extends AbstractController
{
    #[Route('/ajouter-un-carouselmobile', name: 'create_carouselmobile', methods: ['GET', 'POST'])]
    public function createCarouselMobile(CarouselmobileRepository $repository, Request $request, SluggerInterface $slugger): Response
    {
        
        $carouselmobile = new CarouselMobile();

        $form = $this->createForm(CarouselMobileFormType::class, $carouselmobile)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $carouselmobile->setCreatedAt(new DateTime());
            $carouselmobile->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo & */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $carouselmobile, $slugger);
            } //end if($photo)

            $repository->save($carouselmobile, true);

            $this->addFlash('success', "Le carousel a bien été ajouté avec succès !");
            return $this->redirectToRoute('show_dashboard');
        } // end if($form)

        return $this->render('admin/show_carouselmobile.html.twig', [
            'form' => $form->createView(),
        ]);
    } // end createCarouselMobile()

    #[Route('/modifier-un-mobile{id}', name: 'update_mobile', methods: ['GET', 'POST'])]
    public function updateCarouselMobile(Carouselmobile $carouselmobile, Request $request, CarouselmobileRepository $repository, SluggerInterface $slugger): Response
    {
        $currentPhoto = $carouselmobile->getPhoto();

        $form = $this->createForm(CarouselMobileFormType::class, $carouselmobile, [
            'photo' => $currentPhoto,
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $carouselmobile->setCreatedAt(new DateTime());
            $carouselmobile->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($photo, $carouselmobile, $slugger);
                # Si une nouvelle photo est uploadé on va supprimer l'ancienne :
                unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $currentPhoto);
            } else {
                $carouselmobile->setPhoto($currentPhoto);
            } // end if($photo)

            $repository->save($carouselmobile, true);

            $this->addFlash('success', "Le carousel a bien eté modifié avec succès !");
            return $this->redirectToRoute(('show_dashboard'));
        }
        return $this->render('admin/show_carouselmobile.html.twig', [
            'form' => $form->createView(),
            'carouselmobile' => $carouselmobile

        ]);
    } // end updateCarouselMobile()


    #[Route('archiver-un-carouselmobile/{id}', name: 'soft_delete_carouselmobile', methods: ['GET'])]
    public function softDeleteCarouselMobile(CarouselMobile $carouselmobile, CarouselmobileRepository $repository): Response
    {
        $carouselmobile->setDeletedAt(new DateTime());

        $repository->save($carouselmobile, true);

        $this->addFlash('success', "Le carousel a bien été archivé.");
        return $this->redirectToRoute('show_dashboard');
    } // end softDeleteCarouselMobile

    #[Route('restaurer-un-carouselmobile/{id}', name: 'restore_carouselmobile', methods: ['GET'])]
    public function restoreCarouselMobile(CarouselMobile $carouselmobile, CarouselmobileRepository $repository): Response
    {
        $carouselmobile->setDeletedAt(null);

        $repository->save($carouselmobile, true);

        $this->addFlash('success', "Le carousel a bien été restauré");
        return $this->redirectToRoute(('show_archive'));
    } // end restoreCarouselMobile

    #[Route('supprimer-un-carouselmobile/{id}', name: 'hard_delete_carouselmobile', methods: ['GET'])]
    public function hardDeleteCarousel(CarouselMobile $carouselmobile, CarouselmobileRepository $repository): Response
    {
        $photo = $carouselmobile->getPhoto();

        $repository->remove($carouselmobile, true);

        unlink($this->getParameter('uploads_dir') . DIRECTORY_SEPARATOR . $photo);

        $this->addFlash('success', "Le carousel a bien été supprimé définitivement !");
        return $this->redirectToRoute(('show_archive'));
    } // end hardDeleteCarouselMobile


    #[Route('supprimer-tous-carouselmobile-archive', name: 'delete_all_carouselmobile_archive', methods: ['GET'])]
    public function deleteAllCarouselMobileArchive(EntityManagerInterface $entityManager): Response
    {
        $carouselmobiles = $entityManager->getRepository(CarouselMobile::class)->findAllArchived();

        foreach ($carouselmobiles as $carouselmobile) {
            $entityManager->remove($carouselmobile);
        }

        $entityManager->flush();

        $this->addFlash('success', "Toutes les images archivées ont bien été supprimées définitivement du carousel !");
        return $this->redirectToRoute(('show_archive'));
    } // end deleteAllCarouselMobileArchive

    private function handleFile(UploadedFile $photo, Carouselmobile $carouselmobile, SluggerInterface $slugger)
    {
        $extension = "." . $photo->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $carouselmobile->setPhoto($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', "Le fichier de la photo ne s'est pas importé correctement. Veuillez réessayer." . $exception->getMessage());
        } // end catch()
    } // end handleFile()
    
}
