<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => InvoiceStatus::class,
                'choice_label' => 'displayName',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('priceMin', MoneyType::class, [
                "mapped" => false,
                "label" => "Montant minimum",
                "required" => false,
                "invalid_message" => "La valeur entrée n'est pas valide",
                "attr" => [
                    'placeholder' => "Entrez un montant minimum"
                ],
                "constraints" => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 0,
                        'message' => "Le montant minimum ne peut pas être moins de {{ compared_value }}"
                    ])
                ]
            ])
            ->add('priceMax', MoneyType::class, [
                "mapped" => false,
                "label" => "Montant maximum",
                "required" => false,
                "invalid_message" => "La valeur entrée n'est pas valide",
                "attr" => [
                    'placeholder' => "Entrez un montant maximum"
                ],
                "constraints" => [
                    new Assert\GreaterThanOrEqual([
                        "propertyPath" => "priceMin",
                        'message' => "Le montant minimum ne peut pas en dessous du prix minimum ({{ compared_value }})"
                    ])
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
