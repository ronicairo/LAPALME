<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom",
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
            ->add('email', TextType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.',
                        ])]
            ])
            ->add('message', TextareaType::class, [
                'label' => "Message",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.',
                        ]),
                    new Length([
                        'min' => 4,
                        'max' => 600,
                        'minMessage' => 'Votre message doit comporter au minimum {{ limit }} caractères. (message : {{ value }})',
                        'maxMessage' => 'Votre message doit comporter au maximum {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
