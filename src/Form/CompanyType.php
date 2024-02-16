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
                'constraints' => [
                    new Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'message' => 'Veuillez renseigner un nom valide',
                    ])
                ],

            ])
            ->add('address', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => 'Ex: 12 rue des fontaines',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(\d+\s*,?\s*\w+(?:\s+\w+)*\d*\s+[A-Za-z\s]+)$/',
                        'message' => 'Veuillez renseigner une adresse valide',
                    ])
                ],
            ])
            ->add('postalCode', NumberType::class, [
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
            ->add('companyNumber', TextType::class, [
                        'label' => 'Siret',
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
