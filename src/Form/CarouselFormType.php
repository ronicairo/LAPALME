<?php

namespace App\Form;

use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CarouselFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label' => "Image ordinateur",
                'data_class' => null,
                'mapped' => false,
                'attr' => [
                    'value' => $options['photo'] !== null ? $options['photo'] : ''
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'maxSize' => '5M'
                    ]),
                    new NotBlank([
                        'message' =>'Veuillez ajouter une photo (jpg/png accepté)',
                        ])
                ]
            ])
            ->add('photomobile', FileType::class, [
                'label' => "Image mobile",
                'data_class' => null,
                'mapped' => false,
                'attr' => [
                    'value' => $options['photomobile'] !== null ? $options['photomobile'] : ''
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'maxSize' => '5M'
                    ]),
                    new NotBlank([
                        'message' =>'Veuillez ajouter une photo (jpg/png accepté)',
                        ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options(['photo','photomobile']) === null ? 'Créer' : 'Modifier',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carousel::class,
            'allow_file_upload' => true,
            'photo' => null,
            'photomobile' => null,
        ]);
    }
}
