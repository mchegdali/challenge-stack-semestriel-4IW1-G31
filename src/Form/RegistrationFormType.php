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

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['attr' => [
                'placeholder' => 'exemple@mail.com',
            ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom de famille',
                'mapped' => false,
                'required' => true,
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'required' => true,
            ])
            ->add('company_name', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'mapped' => false,
                'required' => true,
            ])
            ->add('company_number', TextType::class, [
                'label' => 'Siret',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '457 450 625 90556'
                ]
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Code Postal',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '00000'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => '16 rue des templiers'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Paris'
                ]
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
