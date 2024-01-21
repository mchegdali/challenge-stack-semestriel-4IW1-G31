<?php

namespace App\Form;

use App\Entity\Quote;
use App\Entity\QuoteStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => QuoteStatus::class,
                'choice_label' => 'name',
                "expanded" => true,
                "multiple" => true,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
