<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisir un nom de service',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Veuillez renseigner un nom valide',
                    ])
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'invalid_message' => 'Veuillez saisir un prix valide',
                'attr' => [
                    'placeholder' => 'Saisir un prix',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'message' => 'Taxe invalide, veuillez respecter le format suivant ex: 10 / 10.50 / 5.24',
                    ]),
                ],
            ])
            ->add('tax', EntityType::class, [
                'label' => "Taxe",
                'class' => 'App\Entity\Tax',
                'choice_label' => 'value',
                'placeholder' => 'Sélectionner une taxe',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
