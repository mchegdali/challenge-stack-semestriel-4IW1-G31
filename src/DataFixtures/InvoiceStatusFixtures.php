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
        $refundStatus->setBgColor("bg-blue-600");
        $refundStatus->setTextColor("text-white");
        $refundStatus->setBorderColor("border-blue-600");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("paid");
        $refundStatus->setDisplayName("Payée");
        $refundStatus->setBgColor("bg-green-700");
        $refundStatus->setTextColor("text-white");
        $refundStatus->setBorderColor("border-green-700");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("late");
        $refundStatus->setDisplayName("En retard");
        $refundStatus->setBgColor("bg-red-500");
        $refundStatus->setTextColor("text-white");
        $refundStatus->setBorderColor("border-red-500");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("cancelled");
        $refundStatus->setDisplayName("Annulée");
        $refundStatus->setBgColor("bg-black");
        $refundStatus->setTextColor("text-white");
        $refundStatus->setBorderColor("border-black");
        $manager->persist($refundStatus);

        $refundStatus = new InvoiceStatus();
        $refundStatus->setName("refund");
        $refundStatus->setDisplayName("Remboursée");
        $refundStatus->setBgColor("bg-gray-700");
        $refundStatus->setTextColor("text-white");
        $refundStatus->setBorderColor("border-gray-700");
        $manager->persist($refundStatus);

        $manager->flush();
    }
}
