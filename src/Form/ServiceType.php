<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisir un nom de service',
                ],
            ])
            ->add('price', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisir un prix',
                ],
            ])
            ->add('tax', EntityType::class, [
                'label' => false,
                'class' => 'App\Entity\Tax',
                'choice_label' => 'value',
                'placeholder' => 'Sélectionner une taxe',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
