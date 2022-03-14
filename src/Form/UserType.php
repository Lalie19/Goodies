<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('role')
            ->add('speudo')
            ->add('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
