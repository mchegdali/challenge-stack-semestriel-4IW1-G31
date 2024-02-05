<?php

namespace App\Form;

use App\Entity\Tax;
use App\Entity\Quote;
use DateTimeImmutable;
use App\Entity\Service;
use App\Entity\QuoteItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class QuoteItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'w-1/4'
                ]
            ])
            ->add('tax', EntityType::class, [
                'label' => 'Tax',
                'placeholder' => '-- Choisir une tax --',
                'class' => Tax::class,
                'choice_label' => function (Tax $tax) {
                    return $tax->getValue();
                }
            ])
            ->add('priceExcludingTax', MoneyType::class, [
                'label' => 'Prix HT',
                'attr' => [
                    'class' => 'w-1/4'
                ]
            ])
            //pour priceIncludingTax faire calcul dans controller
            //pour taxAmount faire calcul dans controller
            //on attribue quote dans le controller

            ->add('service', EntityType::class, [
                'label' => 'Service',
                'attr' => [
                    'class' => 'service-select'
                ],
                'placeholder' => '-- Choisir un service --',
                'class' => Service::class,
                'choice_label' => function (Service $service) {
                    return sprintf('%s (€%s)', $service->getName(), $service->getPrice());
                },
                'choice_attr' => function (Service $service) {
                    return ['data-price' => $service->getPrice()];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteItem::class,
        ]);
    }
}
