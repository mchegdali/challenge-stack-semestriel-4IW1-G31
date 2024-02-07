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
                $invoiceNumber = date("Y") . "-" . $uuidParts[array_key_last($uuidParts)] . "-" . str_pad($i, 3, "0", STR_PAD_LEFT);
                $invoice->setCustomer($customer);
                $invoice->setStatus($faker->randomElement($status));
                $invoice->setCompany($faker->randomElement($companies));
                $invoice->setCreatedAt($faker->dateTimeBetween('2020-01-01', '2024-01-01'));
                $invoice->setInvoiceNumber($invoiceNumber);
                $manager->persist($invoice);

                $payment = new Payment();
                $paymentDueAt = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('2020-01-01', '2024-01-01'));
                $payment->setDueAt($paymentDueAt);
                $payment->setPaidAt(DateTimeImmutable::createFromMutable($faker->dateTimeInInterval($paymentDueAt->format("Y-m-d"), '+ 90 days')));
                $payment->setInvoice($invoice);
                $payment->setPaymentMethod($faker->randomElement($paymentMethods));
                $manager->persist($payment);

                $invoice->setPayment($payment);
                $manager->persist($invoice);

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
