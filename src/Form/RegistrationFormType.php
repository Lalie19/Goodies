<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "votre adresse de courriel : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre adresse de courriel",
                ],

            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'votre mot de passe :',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'entrez votre mot de passe ici',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} characteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => "votre prénom : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre prénom ici",
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => "votre nom : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre nom ici",
                ],
            ])
            ->add('adress', TextType::class, [
                'label' => "votre adresse : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre numéro et rue ici",
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => "votre numéro de téléphone : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre numéro de téléphone ici",
                ],
            ])
            ->add('speudo', TextType::class, [
                'label' => "votre speudo : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre speudo ici",
                ],
            ])
            ->add('picture', TextType::class, [
                'label' => "votre image : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre image ici",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
