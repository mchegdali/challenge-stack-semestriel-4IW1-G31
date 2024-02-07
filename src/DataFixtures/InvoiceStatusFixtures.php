<?php

namespace App\DataFixtures;

use App\Entity\InvoiceStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InvoiceStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("created");
        $refundStatus->setDisplayName("Créée");
        $refundStatus->setBgColor("blue-600");
        $refundStatus->setTextColor("white");
        $refundStatus->setBorderColor("blue-600");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("paid");
        $refundStatus->setDisplayName("Payée");
        $refundStatus->setBgColor("green-700");
        $refundStatus->setTextColor("white");
        $refundStatus->setBorderColor("green-700");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("late");
        $refundStatus->setDisplayName("En retard");
        $refundStatus->setBgColor("red-500");
        $refundStatus->setTextColor("white");
        $refundStatus->setBorderColor("red-500");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("cancelled");
        $refundStatus->setDisplayName("Annulée");
        $refundStatus->setBgColor("black");
        $refundStatus->setTextColor("white");
        $refundStatus->setBorderColor("black");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("refund");
        $refundStatus->setDisplayName("Remboursée");
        $refundStatus->setBgColor("gray-700");
        $refundStatus->setTextColor("white");
        $refundStatus->setBorderColor("gray-700");
        $manager->persist($refundStatus);

        $manager->flush();
    }
}
