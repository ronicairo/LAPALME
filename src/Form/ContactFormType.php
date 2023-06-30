<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide. Veuillez entrer votre nom.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 180,
                        'minMessage' => 'Votre nom doit comporter au minimum {{ limit }} caractères. (NOM : {{ value }})',
                        'maxMessage' => 'Votre nom doit comporter au maximum {{ limit }} caractères.',
                    ])
                ]

            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide. Veuillez entrer votre prénom.',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 180,
                        'minMessage' => 'Votre prénom doit comporter au minimum {{ limit }} caractères. (PRÉNOM : {{ value }})',
                        'maxMessage' => 'Votre prénom doit comporter au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide. Veuillez entrer votre email.',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir un email valide.',
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => "Message",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide. Veuillez entrer votre message.',
                    ]),
                    new Length([
                        'min' => 20,
                        'max' => 600,
                        'minMessage' => 'Votre message doit comporter au minimum {{ limit }} caractères. (MESSAGE : {{ value }})',
                        'maxMessage' => 'Votre message doit comporter au maximum {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-2 btn btn-outline-light col-4"
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'fr'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
