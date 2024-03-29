<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        for ($i = 0; $i < 10; $i++) { // Génère 20 entreprises
            $company = new Company();

            $company->setName($faker->company);
            $company->setCompanyNumber($faker->siret);
            $company->setAddress($faker->address);
            $company->setPostalCode($faker->postcode);
            $company->setCity($faker->city);

            $manager->persist($company);
        }

        $manager->flush();
    }
}
