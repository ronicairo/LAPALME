<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Laissez votre commentaire ici",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>'Veuillez ajouter un message',
                        ])
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut être vide.',
                        ])]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Commentez",
                'label_html' => true, # Permet d'interpréter le HTML dans le label
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-5 btn btn-outline-light col-5"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
