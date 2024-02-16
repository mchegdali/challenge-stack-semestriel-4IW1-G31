<?php
namespace App\Form;

use App\Entity\Tax;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Regex;

class TaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', NumberType::class, [
                'label' => 'Taxe',
                'invalid_message' => 'Veuillez saisir un nombre',
                'attr' => [
                    'placeholder' => 'Entrer une taxe',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^^(\d{1,2}(\.\d{1})?|100)$/',
                        'message' => 'Veuillez rentrer une taxe valide',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tax::class,
        ]);
    }
}
