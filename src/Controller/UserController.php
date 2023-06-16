<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/inscription', name:'register', methods: ['GET', 'POST'])]
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


            # On doit resseter manuellement la valeur du password, car par défaut il n'est pas hashé.
            # Pour cela, nous devons utiliser une méthode de hashage appelée hashPassword() :
            #   => cette méthode attend 2 arguments : $user, $plainPassword
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            $repository->save($user, true);

            $this->addFlash('success', "Votre inscription a été correctement enregistrée");
            //return $this->redirectToRoute('app_login');

        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user/register_form.html.twig', [
            'form' => $form->createView(),
            'users'=>$users
        ]);
    }

    // ------------------------------ HARD-DELETE-ARTICLE -------------------------------
#[Route('/supprimer-un-user/{id}', name: 'soft_delete_user', methods: ['GET'])]
public function softDeleteUser(User $user, UserRepository $repository): Response

{

    $user->setDeletedAt(new DateTime());

    $repository->remove($user, true);

    $this->addFlash('success', "Le compte a bien été supprimé.");
    // return $this->render('admin/register_form.html.twig');
    return $this->redirectToRoute('register');

} // end hardDeleteArticle()

#[Route('/modifier-un-user{id}', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateChambre(User $user, Request $request, UserRepository $repository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());
        

            // $user->setAuthor($this->getUser());

            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );
            $repository->save($user, true);

            $this->addFlash('success', "L'utilisateur a bien eté modifié avec succès !");
            // return $this->redirectToRoute(('show_dashboard'));
        }
        $users=$entityManager->getRepository(User::class)->findAll();
        return $this->render('user/register_form.html.twig', [
            'form' => $form->createView(),
            'users' => $users

        ]);
    }

}
