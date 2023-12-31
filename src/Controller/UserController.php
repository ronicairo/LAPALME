<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/inscription', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = new User();

        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());

            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $user->setRoles(['ROLE_ADMIN']);

            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            $repository->save($user, true);

            $this->addFlash('success', "Votre inscription a été correctement enregistrée");
            return $this->redirectToRoute('show_dashboard');
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user/register_form.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    #[Route('/supprimer-un-user/{id}', name: 'hard_delete_user', methods: ['GET'])]
    public function softDeleteUser(User $user, UserRepository $repository): Response

    {

        $user->setDeletedAt(new DateTime());

        $repository->remove($user, true);

        $this->addFlash('success', "Le compte a bien été supprimé.");
        // return $this->render('admin/register_form.html.twig');
        return $this->redirectToRoute('show_dashboard');
    } // end hardDeleteArticle()


    #[Route('/changer-mon-mot-de-passe', name: 'change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserRepository $repository, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # Récupérer le User en BDD
            $user = $repository->find($this->getUser());


            // ------ VERIFICATION DU MOT DE PASSE ------ //
            $currentPassword = $form->get('currentPassword')->getData();

            if (!$hasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('warning', "Le mot de passe actuel n'est pas valide");
                return $this->redirectToRoute('change_password');
            }

            $user->setUpdatedAt(new DateTime());

            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($hasher->hashPassword($user, $plainPassword));

            $repository->save($user, true);

            $this->addFlash('success', "Votre mot de passe a bien été modifié");
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('user/change_password_form.html.twig', [
            'form' => $form->createView()
        ]);
    } // end changePassword()

}
