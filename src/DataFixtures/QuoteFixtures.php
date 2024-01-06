<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Quote;
use App\Entity\Customer;
use App\Entity\Company;
use App\Entity\QuoteStatus;
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

        for ($i = 0; $i < 80; $i++) {
            $quote = new Quote();

            $quote->setCustomer($faker->randomElement($customers));
            $quote->setStatus($faker->randomElement($status));
            $quote->setCompany($faker->randomElement($companies));
            $quote->setCreatedAt($faker->dateTimeBetween('2020-01-01', '2024-01-01'));
            $quote->setQuoteNumber($faker->unique()->numberBetween(1000, 9999));

            $manager->persist($quote);
        }

        $manager->flush();
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
