<?php

namespace App\Form;

use App\Entity\Quote;
use DateTimeImmutable;
use App\Entity\Customer;
use App\Entity\QuoteStatus;
use App\Form\QuoteItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuoteCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quoteitems', CollectionType::class, [
                'entry_type' => QuoteItemType::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('customer', EntityType::class, [
                'label' => 'Client',
                'placeholder' => '-- Choisir un client --',
                'class' => Customer::class,
                'choice_label' => function (Customer $customer) {
                    return $customer->getName();
                }
            ])
            ->add('status', EntityType::class, [
                'label' => 'Statut',
                'placeholder' => '-- Choisir un statut --',
                'class' => QuoteStatus::class,
                'choice_label' => 'displayName'
            ])
            ->add('valider', SubmitType::class, [
//                'attr' => [
//                    'class' => 'bg-blue-800 text-white font-bold py-2 px-4 rounded w-auto items-center flex justify-center gap-2 text-sm mt-4',
//                ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
