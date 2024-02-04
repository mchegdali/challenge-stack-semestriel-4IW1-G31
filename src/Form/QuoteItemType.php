<?php

namespace App\Form;

use App\Entity\Tax;
use App\Entity\Quote;
use DateTimeImmutable;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuoteItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            ->add('status', EntityType::class, [
                'label' => 'Tax',
                'placeholder' => '-- Choisir une tax --',
                'class' => Tax::class,
                'choice_label' => function (Tax $tax) {
                    return $tax->getValue();
                }
            ])
            //pour priceExcludingTax faire calcul dans controller
            //pour priceIncludingTax faire calcul dans controller
            //pour taxAmount faire calcul dans controller
            //on attribue quote dans le controller

            ->add('service', EntityType::class, [
                'label' => 'Service',
                'placeholder' => '-- Choisir un service --',
                'class' => Service::class,
                'choice_label' => function (Service $service) {
                    return $service->getName();
                }
            ])
            ->add('services', CollectionType::class, [
                'entry_type' => ServiceType::class,
                'label' => 'Services',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
