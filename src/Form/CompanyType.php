<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'entreprise",
                'attr' => [
                    'placeholder' => 'Entrer un nom',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => 'Entrer une adresse',
                ],
            ])
            ->add('postal_code', NumberType::class, [
                'label' => "Code Postale",
                'attr' => [
                    'placeholder' => 'Entrer un code postal',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Code postal invalide',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => 'Entrer une ville',
                ],
            ])
            ->add('company_number', TextType::class, [
                'label' => 'Siret',
                'attr' => [
                    'placeholder' => '45745062590556'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{15}$/',
                        'message' => 'Siret invalide',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
