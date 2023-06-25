<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => false,
                'attr' =>[
                    'placeholder' => "Mot de passe actuel"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Nouveau mot de passe",
                        'autocomplete' => 'new-password'
                    ]

                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Réptétez le mot de passe",
                        'autocomplete' => "new-password"
                    ]

                ],
                'invalid_message' => "Les mots de passe ne sont pas identiques."
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider mon changement",
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto btn btn-outline-light col-10"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
