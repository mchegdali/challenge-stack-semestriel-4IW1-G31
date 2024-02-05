<?php

namespace App\Form;

use App\Entity\PaymentStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => PaymentStatus::class,
                'choice_label' => 'name',
                'label' => 'Filtrer par statut',
                "expanded" => true,
                "multiple" => true,
                "mapped" => true,
                "required" => false,
            ])->add('dueDate', ChoiceType::class, [
                "mapped" => false,
                "required" => false,
                "expanded" => true,
                "multiple" => false,
                'placeholder' => false,
                'label' => "Filtrer par date d'échéance",
                'choices' => [
                    "Les 3 derniers mois" => 0,
                    "Les 6 derniers mois" => 1,
                    "2024" => 2,
                    "2023" => 3,
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
