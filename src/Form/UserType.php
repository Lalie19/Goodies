<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class, [
                'label' => "Votre adresse de courtiel : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre adresse de courtiel ",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "ce champs ne doit pas être vide",
                    ]),
                ]
            ])
            ->add('adress')
            ->add('phone')
            ->add('password')
            // ->add('role')
            ->add('speudo')
            ->add('picture', FileType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
