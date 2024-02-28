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
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("paid");
        $status->setDisplayName("Payée");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("late");
        $status->setDisplayName("Retard");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("cancelled");
        $status->setDisplayName("Annulée");
        $manager->persist($status);

        $status = new InvoiceStatus();
        $status->setName("refund");
        $status->setDisplayName("Remboursée");
        $manager->persist($status);

        $manager->flush();
    }
}
