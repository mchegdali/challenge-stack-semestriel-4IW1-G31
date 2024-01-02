<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');
        $populator = new \Faker\ORM\Propel\Populator($faker);

        $populator->addEntity(Service::class, 200);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TaxFixtures::class,
        ];
    }
}
