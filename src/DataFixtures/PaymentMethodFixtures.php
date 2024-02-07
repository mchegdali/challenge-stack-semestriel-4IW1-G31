<?php

namespace App\DataFixtures;

use App\Entity\PaymentMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $paymentMethods = [
            "card" => "Carte bancaire",
            "check" => "Chèque",
            "cash" => "Espèces",
            "transfer" => "Virement"
        ];

        foreach ($paymentMethods as $key => $value) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->setName($key);
            $paymentMethod->setDisplayName($value);
            $manager->persist($paymentMethod);
        }

        $manager->flush();
    }
}
