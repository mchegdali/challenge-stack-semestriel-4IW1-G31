<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('tax', EntityType::class, [
                'class' => 'App\Entity\Tax',
                'choice_label' => 'value',
                'placeholder' => 'Choose an option',
            ])
            // Ajouter un bouton de soumission
            ->add('save', SubmitType::class, [
                'label' => 'Save', // Libellé du bouton
                'attr' => ['class' => 'btn btn-primary'], // Classes CSS pour le style du bouton (facultatif)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
