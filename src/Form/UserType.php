<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('company', EntityType::class, [
                'label' => 'Entreprise',
                'class' => 'App\Entity\Company',
                'choice_label' => 'name',
                'placeholder' => 'Sélectionner une entreprise',
            ])
            ->add('role', EntityType::class, [
                'label' => 'Role',
                'class' => 'App\Entity\Role',
                'choice_label' => 'name',
                'placeholder' => 'Sélectionner un role',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
