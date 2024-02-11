<?php

namespace App\Form;

use App\Entity\InvoiceStatus;
use App\Validator\PriceMax;
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
                "label" => "Montant minimum (en €)",
                "currency" => false,
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
                "label" => "Montant maximum (en €)",
                "currency" => false,
                "required" => false,
                "invalid_message" => "La valeur entrée n'est pas valide",
                "attr" => [
                    'placeholder' => "Entrez un montant maximum"
                ],
                "constraints" => [
                    [
                        new Assert\GreaterThanOrEqual([
                            'value' => 0,
                            'message' => "Le montant maximum ne peut pas être négatif"
                        ]),
                        new PriceMax()
                    ]
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
                ],
                "constraints" => [
                    new Assert\GreaterThanOrEqual([
                        'value' => $builder->get('minDate')->getData(),
                        'message' => "La date de fin ne peut pas être antérieure à la date de début ({{ compared_value }})"
                    ])
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
