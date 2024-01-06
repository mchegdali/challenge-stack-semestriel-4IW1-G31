<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $taxes = $manager->getRepository(Tax::class)->findAll();

        if (!$taxes) {
            throw new \Exception('Aucune taxe trouvée, assurez-vous que TaxFixtures est chargé en premier.');
        }

        for ($i = 0; $i < 200; $i++) {
            $service = new Service();
            $service->setName($faker->words(3, true));
            $service->setPrice($faker->randomFloat(2, 200, 500));
            $service->setTax($faker->randomElement($taxes));

            $manager->persist($service);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TaxFixtures::class,
        ];
    }
}
