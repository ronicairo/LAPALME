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
                    'class' => 'editor' # Pour activer CKEditor
                ],
                'constraints' => [
                    new NotBlank(),
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
                'label' => "Commenter <i class='bi bi-send'></i>",
                'label_html' => true, # Permet d'interpréter le HTML dans le label
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-3 col-3 btn btn-warning"
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
