<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Customer;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) { //10 clients générés
            $customer = new Customer();

            $customer->setName($faker->name);
            $customer->setAddress($faker->address);
            $customer->setPostalCode($faker->postcode);
            $customer->setCity($faker->city);
            $customer->setCustomerNumber($faker->unique()->numberBetween(1000, 9999));
            $customer->setEmail($faker->email);

            // Pour l'instant, on laisse 'quotes' et 'invoices' à null


            $manager->persist($customer);
        }

        $manager->flush();
    }
}
