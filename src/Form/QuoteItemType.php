<?php

namespace App\Form;

use App\Entity\Tax;
use App\Entity\Service;
use App\Entity\QuoteItem;
use App\Repository\ServiceRepository;
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
            ->add('service', EntityType::class, [
                'label' => 'Service',
                'placeholder' => '-- Choisir un service --',
                'class' => Service::class,
                'choice_label' => function (Service $service) {
                    return sprintf('%s (€%s)', $service->getName(), $service->getPrice());
                },
                'choice_attr' => function (Service $service) {
                    return ['data-price' => $service->getPrice()];
                },
                'query_builder' => function (ServiceRepository $serviceRepository) {
                    return $serviceRepository->createQueryBuilder('s')
                        ->where('s.isArchived is null')
                        ->orWhere('s.isArchived = false')
                        ; // On ne retient pas les services archivés
                },
            ])
            ->add('priceExcludingTax', MoneyType::class, [
                'label' => 'Prix HT',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
            ])
            ->add('tax', EntityType::class, [
                'label' => 'Taxe',
                'placeholder' => '-- Choisir une taxe --',
                'class' => Tax::class,
                'choice_label' => function (Tax $tax) {
                    return $tax->getValue();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteItem::class,
        ]);
    }
}
