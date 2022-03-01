<?php

namespace App\Form;

use App\From\Builder\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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

            ->add('subject', TextType::class, [
                'label' => "Sujet de votre message : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez le sujet de votre message",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "ce champs ne doit pas être vide",
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => "le sujet doit contenir au minimun {{ limit }} caractéres",
                        'max' => 100,
                        'maxMessage' => "le sujet ne doit pas contenir exéder {{ limit }} caractéres",
                    ]),
                ]
            ])

            ->add('message', TextareaType::class, [
                'label' => "votre message : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre message",
                    'row' => 4,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "ce champs ne doit pas être vide",
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => "le message doit contenir au minimun {{ limit }} caractéres",
                        'max' => 1000,
                        'maxMessage' => "le message ne doit pas contenir exéder {{ limit }} caractéres",
                    ]),
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
