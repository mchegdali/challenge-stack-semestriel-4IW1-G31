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
        $status->setBgColor("primary");
        $status->setTextColor("white");
        $status->setBorderColor("primary");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("paid");
        $status->setDisplayName("Payée");
        $status->setBgColor("success");
        $status->setTextColor("white");
        $status->setBorderColor("success");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("late");
        $status->setDisplayName("Retard");
        $status->setBgColor("error");
        $status->setTextColor("white");
        $status->setBorderColor("error");
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
        $status->setBgColor("grey");
        $status->setTextColor("white");
        $status->setBorderColor("grey");
        $manager->persist($status);

        $manager->flush();
    }
}
