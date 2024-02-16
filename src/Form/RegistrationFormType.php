<?php
// src/Form/RegistrationFormType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Veuillez renseigner un email valide',
                    ])
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.',
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Veuillez renseigner un nom valide',
                    ])
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Veuillez renseigner un prénom valide',
                    ])
                ],
            ])
            ->add('company_name', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'message' => 'Veuillez renseigner un nom valide',
                    ])
                ],
            ])

            ->add('company_number', TextType::class, [
                'label' => 'Siret',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '12312312312345'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{14}$/',
                        'message' => 'Siret invalide, veuillez respecter le format suivant ex: 12312312312345',
                    ]),
                ],
            ])

            ->add('postal_code', TextType::class, [
                'label' => 'Code Postal',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '00000'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Veuillez renseigner un code postal valide',
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '16 rue des templiers'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(\d+\s*,?\s*\w+(?:\s+\w+)*\d*\s+[A-Za-z\s]+)$/',
                        'message' => 'Veuillez renseigner une adresse valide',
                    ])
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Paris'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za-z\s-]+$/',
                        'message' => 'Veuillez renseigner une ville valide',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => "S'inscrire",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
