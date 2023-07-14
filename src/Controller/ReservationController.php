<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'register_reservation')]
    public function reservationPlace(Request $request, ReservationRepository $repository, MailerInterface $mailer): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationFormType::class, $reservation)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation->setCreatedAt(new DateTime());
            $reservation->setUpdatedAt(new DateTime());

            $repository->save($reservation, true);
            
           //email
           $email = (new TemplatedEmail())
           ->from('contact@restaurant-lapalme.fr')
           ->to('lapalme60180@gmail.com')
           //->cc('cc@example.com')
           //->bcc('bcc@example.com')
           //->replyTo('fabien@example.com')
           //->priority(Email::PRIORITY_HIGH)
           ->subject("La Palme - Réservation de  " . $reservation->getPrenom() . " "  . $reservation->getNom() . ".")
           ->htmlTemplate('admin/email_reservation.html.twig')

           ->context([
               'reservation' => $reservation,
           ]);

       $mailer->send($email);
          
            $message = "Votre réservation a été prise en compte";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('show_home');
        }

        return $this->render('reservation/register_reservation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modifier-une-reservation{id}', name: 'update_reservation', methods: ['GET', 'POST'])]
public function updateReservation(Reservation $reservation, Request $request, ReservationRepository $repository): Response
{
    $form = $this->createForm(ReservationFormType::class, $reservation)
        ->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $reservation->setCreatedAt(new DateTime());
        $reservation->setUpdatedAt(new DateTime());

        $repository->save($reservation, true);

        $this->addFlash('success', "La reservation a bien eté modifié avec succès !");
        return $this->redirectToRoute(('show_dashboard'));
    }
    return $this->render('reservation/register_reservation.html.twig', [
        'form' => $form->createView(),
        'reservation' => $reservation

    ]);
} // end updateReservation()

#[Route('/archiver-une-reservation/{id}', name: 'soft_delete_reservation', methods: ['GET'])]
public function softDeleteReservation(Reservation $reservation, ReservationRepository $repository): Response
{

    $reservation->setDeletedAt(new DateTime());

    $repository->save($reservation, true);

    $this->addFlash('success', "La réservation a bien été archivée.");
    return $this->redirectToRoute('show_dashboard');

} // end sofdeleteReservation

#[Route('restaurer-une-reservation{id}', name: 'restore_reservation', methods: ['GET'])]
public function restoreReservation(Reservation $reservation, ReservationRepository $repository): Response
{

    $reservation->setDeletedAt(null);

    $repository->save($reservation, true);

    $this->addFlash('success', "La resérvation a bien été restauré");
    return $this->redirectToRoute(('show_archive'));

} // end restoreReservation

#[Route('supprimer-une-reservation/{id}', name: 'hard_delete_reservation', methods: ['GET'])]
public function hardDeleteReservation(Reservation $reservation, ReservationRepository $repository): Response
{

    $repository->remove($reservation, true);
    
    $this->addFlash('success', "La reservation a bien été supprimé définitivement !");
    return $this->redirectToRoute(('show_archive'));

} // end hardDeleteReservation

#[Route('supprimer-toute-reservation-archive', name: 'delete_all_reservation_archive', methods: ['GET'])]
public function deleteAllReservationArchive(EntityManagerInterface $entityManager): Response
{
    $reservations = $entityManager->getRepository(Reservation::class)->findAllArchived();

    foreach ($reservations as $reservation) {
        $entityManager->remove($reservation);
    }

    $entityManager->flush();

    $this->addFlash('success', "Toutes les réservations archivées ont bien été supprimées !");
    return $this->redirectToRoute(('show_archive'));
} // end deleteAllReservationArchive


}
