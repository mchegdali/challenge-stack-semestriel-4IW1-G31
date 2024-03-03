<?php

namespace App\Form;

use App\Entity\Tax;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\Service;
use App\Entity\InvoiceItem;
use App\Repository\ServiceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;

class InvoiceItemType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $this->security->getUser();
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());

        $builder

        ->add('service', EntityType::class, [
            'label' => 'Service',
            'placeholder' => '-- Choisir un service --',
            'class' => Service::class,
            'choice_label' => function (Service $service) {
                return sprintf('%s (€%s)', $service->getName(), $service->getPrice());
            },
            'choice_attr' => function (Service $service) {
                return ['data-price' => $service->getPrice()];
            },
            'query_builder' => function (EntityRepository $er) use ($isAdmin, $user) {
                $qb = $er->createQueryBuilder('c')
                ->where('c.isArchived is null OR c.isArchived = false'); 
                if (!$isAdmin) {
                    $company = $user->getCompany();
                    $qb->where('c.company = :company')
                       ->setParameter('company', $company);
                }
                return $qb;
                },
            ])
            ->add('priceExcludingTax', MoneyType::class, [
                'label' => 'Prix HT',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
            ])
            ->add('tax', EntityType::class, [
                'label' => "Taxe",
                'class' => 'App\Entity\Tax',
                'choice_label' => 'value',
                'placeholder' => 'Sélectionner une taxe',
                'query_builder' => function (EntityRepository $er) use ($isAdmin, $user) {
                    $qb = $er->createQueryBuilder('c');
                    if (!$isAdmin) {
                        $company = $user->getCompany();
                        $qb->where('c.company = :company')
                           ->setParameter('company', $company);
                    }
                    return $qb;
                },
            ]);
            //pour priceIncludingTax faire calcul dans controller
            //pour taxAmount faire calcul dans controller
            //on attribue invoice dans le controller
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceItem::class,
        ]);
    }
}
