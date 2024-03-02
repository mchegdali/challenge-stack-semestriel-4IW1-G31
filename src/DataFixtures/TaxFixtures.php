<?php

namespace App\DataFixtures;

use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $taxes = [0., 5.5, 10, 20];

        foreach ($taxes as $value) {
            $tax = new Tax();
            $tax->setValue($value);
            $manager->persist($tax);
        }

        $manager->flush();
    }

}
