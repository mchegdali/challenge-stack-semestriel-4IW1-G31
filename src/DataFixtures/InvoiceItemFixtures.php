<?php

namespace App\DataFixtures;

use App\Entity\InvoiceItem;
use App\Entity\Service;
use App\Entity\Invoice;
use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InvoiceItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $invoices = $manager->getRepository(Invoice::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();
        $taxes = $manager->getRepository(Tax::class)->findAll();

        if (!$invoices || !$services || !$taxes) {
            throw new \Exception('Assurez-vous que ServiceFixtures, InvoiceFixtures et TaxFixtures soient chargés en premier.');
        }

        foreach ($invoices as $invoice) {
            for ($i = 0; $i < $faker->numberBetween(1, 5); $i++) {
                $priceExcludingTax = $faker->randomFloat(2, 0, 1000);
                $tax = $faker->randomElement($taxes);
                $taxAmount = ($tax->getValue() * $priceExcludingTax) / 100;
                $priceIncludingTax = $priceExcludingTax + $taxAmount;

                $invoiceItem = new InvoiceItem();
                $invoiceItem->setInvoice($invoice);
                $invoiceItem->setQuantity($faker->numberBetween(1, 5));
                $invoiceItem->setService($faker->randomElement($services));
                $invoiceItem->setTax($faker->randomElement($taxes));
                $invoiceItem->setPriceExcludingTax($priceExcludingTax);
                $invoiceItem->setTaxAmount($taxAmount);
                $invoiceItem->setPriceIncludingTax($priceIncludingTax);
                $manager->persist($invoiceItem);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            InvoiceFixtures::class,
            ServiceFixtures::class,
            TaxFixtures::class,
        ];
    }
}
