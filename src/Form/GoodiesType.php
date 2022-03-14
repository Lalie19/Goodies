<?php

namespace App\Form;

use App\Entity\Types;
use App\Entity\Goodies;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GoodiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('types', EntityType::class, [
                'class' => Types::class,
                'choice_label' => 'category',
            ])

            ->add('image', FileType::class, [
                'mapped' => false, 
                'required' => false, 
                'multiple' => false,
                'label' => "uploader une image ",
                'attr' => [
                    'placeholder' => 'uploader une image depuis votre ordinateur'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048K',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                        ],
                    ])
                    ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Goodies::class,
        ]);
    }
}
