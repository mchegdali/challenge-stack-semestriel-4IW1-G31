<?php

namespace App\Form;

use App\Entity\QuoteStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => QuoteStatus::class,
                'choice_label' => 'displayName',
                "expanded" => true,
                "multiple" => true,
                "mapped" => false,
                "required" => false,
            ])->add('priceMin', MoneyType::class, [
                "mapped" => false,
                "label" => "Montant minimum",
                "required" => false,
                "invalid_message" => "La valeur entrée n'est pas valide",
                "attr" => [
                    'placeholder' => "Entrez un montant minimum"
                ]
            ])
            ->add('priceMax', MoneyType::class, [
                "mapped" => false,
                "label" => "Montant maximum",
                "required" => false,
                "invalid_message" => "La valeur entrée n'est pas valide",
                "attr" => [
                    'placeholder' => "Entrez un montant maximum"
                ]
            ])->add('minDate', DateType::class, [
                "mapped" => false,
                "label" => "Date de début",
                "required" => false,
                "invalid_message" => "La date entrée n'est pas valide",
                "widget" => "single_text",
                "attr" => [
                    'placeholder' => "Entrez une date de début"
                ]
            ])->add('maxDate', DateType::class, [
                "mapped" => false,
                "label" => "Date de fin",
                "required" => false,
                "invalid_message" => "La date entrée n'est pas valide",
                "widget" => "single_text",
                "attr" => [
                    'placeholder' => "Entrez une date de fin"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
