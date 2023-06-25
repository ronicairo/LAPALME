<?php

namespace App\Form;

use App\Entity\Galerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GalerieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', FileType::class, [
                'label' => "Image",
                'data_class' => null,
                'mapped' => false,
                'attr' => [
                    'value' => $options['img'] !== null ? $options['img'] : ''
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'maxSize' => '8M'
                    ]),
                    new NotBlank([
                        'message' =>'Veuillez ajouter une photo (jpg/png accepté)',
                        ])
                ]
            ])
            ->add('titre', TextType::class, [
                'label' => "Titre de l'image ",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Veuillez ajouter un titre',
                        ])
                ]
            ])
       
            ->add('submit', SubmitType::class, [
                'label' => ($options['img'] === null) ? 'Créer' : 'Modifier',
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-2 btn btn-outline-light col-4"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Galerie::class,
            'allow_file_upload' => true,
            'img' => null,
  
        ]);
    }
}
