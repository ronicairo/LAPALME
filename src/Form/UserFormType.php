<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => "Choisissez un email",
            'constraints' => [
                new NotBlank([
                    'message' =>'Ce champ ne peut etre vide : {{ value }}'
                ]),
                new Length([
                    'min' => 4,
                    'max' =>180,
                    'minMessage' =>'Votre email doit comporter au minimum {{ limit }} caractères.(email : {{ value }})',
                    'maxMessage' =>'Votre email doit comporter au maximum {{ limit }} caractères.(email : {{ value }})',
                ]),
                new Email([
                    'message' => 'Veuillez saisir un email valide.',
                ]),
            ],
        ])
  
    

            ->add('password', PasswordType::class, [
                'label' => "Choisissez un mot de passe fort",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 255,
                        'minMessage' =>'Votre mot de passe doit comporter au minimum {{ limit }} caractères.',
                        'maxMessage' =>'Votre mot de passe doit comporter au maximum {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
                    ]),
                ],
            ])

            ->add('lastname', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.'
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.'
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Ajouter",
                'validate' => false,
                'attr'=>[
                    'class' => "d-block mx-auto my-2 btn btn-outline-light col-4"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}
