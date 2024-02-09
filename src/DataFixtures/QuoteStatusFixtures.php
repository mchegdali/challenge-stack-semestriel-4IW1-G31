<?php

namespace App\DataFixtures;

use App\Entity\QuoteStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuoteStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $status = new QuoteStatus();
        $status->setDisplayName("Brouillon");
        $status->setName("draft");
        $status->setBgColor("gray-700");
        $status->setTextColor("white");
        $status->setBorderColor("gray-700");
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Envoyé");
        $status->setName("sent");
        $status->setBgColor("blue-600");
        $status->setTextColor("white");
        $status->setBorderColor("blue-600");
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Validé");
        $status->setName("accepted");
        $status->setBgColor("green-700");
        $status->setTextColor("white");
        $status->setBorderColor("green-700");
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Refusé");
        $status->setName("refused");
        $status->setBgColor("red-500");
        $status->setTextColor("white");
        $status->setBorderColor("red-500");
        $manager->persist($status);

        $manager->flush();
    }
}
