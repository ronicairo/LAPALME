<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'class' => "text-white"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.',
                        ])]
            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.',
                        ])]
            ])
            ->add('date', DateTimeType::class, [
                    'widget' => 'single_text',
                    'placeholder' => [
                        'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                        'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Second',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' =>'Veuillez sélectionner une date et un horaire.',
                            ])]
                ])
            ->add('personne', IntegerType::class, [
                'label' => "Nombre de personnes",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut contenir uniquement des caractères numériques (0-9).',
                        ])]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide : {{ value }}'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' =>180,
                        'minMessage' =>'Votre email doit comporter au minimum {{ limit }} caractères.(email : {{ value }})',
                        'maxMessage' =>'Votre email doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Réserver",
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-3 btn btn-warning col-3"
                ]
            ])
        ;
    }
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}