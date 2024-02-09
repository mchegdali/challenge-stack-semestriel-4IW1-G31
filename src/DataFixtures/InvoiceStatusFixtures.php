<?php

namespace App\DataFixtures;

use App\Entity\InvoiceStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InvoiceStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $status = new InvoiceStatus();
        $status->setName("created");
        $status->setDisplayName("Créée");
        $status->setBgColor("blue-600");
        $status->setTextColor("white");
        $status->setBorderColor("blue-600");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("paid");
        $status->setDisplayName("Payée");
        $status->setBgColor("green-700");
        $status->setTextColor("white");
        $status->setBorderColor("green-700");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("late");
        $status->setDisplayName("Retard");
        $status->setBgColor("red-500");
        $status->setTextColor("white");
        $status->setBorderColor("red-500");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("cancelled");
        $status->setDisplayName("Annulée");
        $status->setBgColor("black");
        $status->setTextColor("white");
        $status->setBorderColor("black");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("refund");
        $status->setDisplayName("Remboursée");
        $status->setBgColor("gray-700");
        $status->setTextColor("white");
        $status->setBorderColor("gray-700");
        $manager->persist($status);

        $manager->flush();
    }
}
