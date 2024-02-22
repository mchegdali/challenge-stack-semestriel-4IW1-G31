<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\InvoiceStatus;
use App\Entity\Payment;
use App\Entity\PaymentMethod;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $customers = $manager->getRepository(Customer::class)->findAll();
        $status = $manager->getRepository(InvoiceStatus::class)->findAll();
        $companies = $manager->getRepository(Company::class)->findAll();
        $paymentMethods = $manager->getRepository(PaymentMethod::class)->findAll();

        if (!$customers || !$status || !$companies || !$paymentMethods) {
            throw new \Exception('Assurez-vous que CustomerFixtures, QuoteStatusFixtures, CompanyFixtures, PaymentMethodFixtures soient chargées.');
        }

        foreach ($customers as $customer) {
            $nbCustomerQuotes = count($customer->getQuotes());
            $uuidParts = explode("-", $customer->getId()->toRfc4122());
            for ($i = $nbCustomerQuotes + 1; $i <= $nbCustomerQuotes + 10; $i++) {

                $invoice = new Invoice();
                $createdAt = $faker->dateTimeBetween('2023-01-01', '2024-01-01');

                $invoiceNumber = $createdAt->format('Y') . "-" . $uuidParts[array_key_last($uuidParts)] . "-" . str_pad($i, 3, "0", STR_PAD_LEFT);
                $invoice->setCustomer($customer);
                $invoice->setStatus($faker->randomElement($status));
                $invoice->setCompany($faker->randomElement($companies));
                $invoice->setDueAt(DateTimeImmutable::createFromMutable($createdAt->modify('+ 30 days')));
                $invoice->setCreatedAt($createdAt);
                $invoice->setInvoiceNumber($invoiceNumber);
                $manager->persist($invoice);

                for ($j = 0; $j < 2; $j++) {
                    $payment = new Payment();
                    $payment->setPaidAt(DateTimeImmutable::createFromMutable($faker->dateTimeInInterval($createdAt->format("Y-m-d"), '+ 90 days')));
                    $payment->setInvoice($invoice);
                    $payment->setPaymentMethod($faker->randomElement($paymentMethods));
                    $payment->setAmount($faker->randomFloat(2, 100, 1000));
                    $manager->persist($payment);

                    $invoice->addPayment($payment);
                    $manager->persist($invoice);
                }

                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
            PaymentMethodFixtures::class,
            CustomerFixtures::class,
            InvoiceStatusFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
