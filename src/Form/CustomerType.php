<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Regex;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                // 'constraints' => [
                //     new Regex([
                //         'pattern' => '/^[a-zA-Z]+(?:\s[a-zA-Z]+)?$/',
                //         'message' => 'Veuillez renseigner un nom valide',
                //     ])
                // ],
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => 'Ex: 12 rue des fontaines',
                ],
                // 'constraints' => [
                //     new Regex([
                //         'pattern' => '/^(\d+\s*,?\s*\w+(?:\s+\w+)*\d*\s+[A-Za-z\s]+)$/',
                //         'message' => 'Veuillez renseigner une adresse valide',
                //     ])
                // ],
            ])
            ->add('postal_code', NumberType::class, [
                'label' => "Code postal",
                'invalid_message' => 'Veuillez saisir un nombre valide pour le code postal',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Veuillez renseigner un code postal valide',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => 'Entrer une ville',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za-z\s-]+$/',
                        'message' => 'Veuillez renseigner une ville valide',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Veuillez renseigner un email valide',
                    ])
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => "Numéro de téléphone",
                'required' => false, // Set this to true if the phone number is mandatory
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+?[0-9]+$/',
                        'message' => 'Veuillez renseigner un numéro de téléphone valide',
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
            'data_class' => Customer::class,
        ]);
    }
}
