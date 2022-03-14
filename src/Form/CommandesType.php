<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commandes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommandesType extends AbstractType
{
    // private $translator;

    // public function__construct(TranslatorInterface $translator) {
    //     $this->translator = $translator
    // }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('price')
            ->add('numero_facture')
            ->add('user', EntityType::class, [
                'class' => User::class,
                ''
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
