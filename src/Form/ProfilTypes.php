<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ProfileType extends AbstractType
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
            ->add('phone', TextType::class, [
                'label' => "votre numéro de téléphone : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre numéro ici",
                ],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre numéro de téléphone doit contenir {{ limit }} characteres',
                        // max length allowed by Symfony for security reasons
                    ]),
                ],
            ])
            ->add('adress', TextType::class, [
                'label' => "votre adresse : ",
                'required' => true,
                'attr' => [
                    'placeholder' => "entrez votre numéro et rue ici",
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