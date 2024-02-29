<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $companies = $manager->getRepository(Company::class)->findAll();
        
        $taxes = [0., 5.5, 10, 20];

        foreach ($taxes as $value) {
            $tax = new Tax();
            $tax->setValue($value);
            $tax->setCompany($faker->randomElement($companies));
            $manager->persist($tax);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
