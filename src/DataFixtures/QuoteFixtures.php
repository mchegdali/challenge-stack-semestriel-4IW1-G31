<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Quote;
use App\Entity\Customer;
use App\Entity\Company;
use App\Entity\QuoteStatus;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class QuoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $customers = $manager->getRepository(Customer::class)->findAll();
        $status = $manager->getRepository(QuoteStatus::class)->findAll();
        $companies = $manager->getRepository(Company::class)->findAll();

        if (!$customers || !$status || !$companies) {
            throw new \Exception('Assurez-vous que CustomerFixtures, QuoteStatusFixtures, et CompanyFixtures sont chargés en premier.');
        }

        //Les QuoteItem sont attribués dans QuoteItemFixtures

        foreach ($customers as $customer) {
            $nbCustomerQuotes = count($customer->getQuotes());
            $uuidParts = explode("-", $customer->getId()->toRfc4122());
            for ($i = $nbCustomerQuotes + 1; $i <= $nbCustomerQuotes + 10; $i++) {

                $quote = new Quote();
                $quoteNumber = date("Y") . "-" . $uuidParts[array_key_last($uuidParts)] . "-" .  str_pad($i, 3, "0", STR_PAD_LEFT);
                $quote->setCustomer($customer);
                $quote->setStatus($faker->randomElement($status));
                $quote->setCompany($faker->randomElement($companies));
                $quote->setCreatedAt($faker->dateTimeBetween('2020-01-01', '2024-01-01'));
                $quote->setQuoteNumber($quoteNumber);

                $manager->persist($quote);
                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
            QuoteStatusFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
