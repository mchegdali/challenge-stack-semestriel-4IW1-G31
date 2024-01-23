<?php

namespace App\Form;

use App\Entity\Quote;
use DateTimeImmutable;
use App\Entity\QuoteStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuoteCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quoteitems', CollectionType::class, [
                'entry_type' => QuoteItemType::class,
                'label' => 'Items',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('customer', CollectionType::class, [
                'entry_type' => CustomerType::class, //TODO: faire le customer type
                'label' => 'Customer',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('status', EntityType::class, [
                'label' => 'Status',
                'placeholder' => '-- Choisir un status --',
                'class' => QuoteStatus::class,
                'choice_label' => function (QuoteStatus $status) {
                    return $status->getName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
            'createdAt' => new DateTimeImmutable()
        ]);
    }
}
