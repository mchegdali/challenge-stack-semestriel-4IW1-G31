<?php
namespace App\Form;

use App\Entity\Tax;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaxeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', NumberType::class, [
                'label' => 'Tax Value', // Définit le libellé du champ
                'attr' => [
                    'class' => 'form-control', // Ajoute une classe pour Bootstrap
                    'placeholder' => 'Enter tax value', // Ajoute un placeholder
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add', // Texte du bouton de soumission
                'attr' => ['class' => 'btn btn-primary mt-3'], // Ajoute des classes pour le style Bootstrap
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tax::class,
        ]);
    }
}
