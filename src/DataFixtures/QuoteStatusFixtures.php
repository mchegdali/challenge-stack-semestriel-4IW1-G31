<?php

namespace App\DataFixtures;

use App\Entity\QuoteStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuoteStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $draftStatus = new QuoteStatus();
        $draftStatus->setName("Brouillon");
        $draftStatus->setColor("gray");
        $manager->persist($draftStatus);

        $sentStatus = new QuoteStatus();
        $sentStatus->setName("Envoyé");
        $sentStatus->setColor("blue");
        $manager->persist($sentStatus);

        $validatedStatus = new QuoteStatus();
        $validatedStatus->setName("Validé");
        $validatedStatus->setColor("green");
        $manager->persist($validatedStatus);

        $rejectedStatus = new QuoteStatus();
        $rejectedStatus->setName("Refusé");
        $rejectedStatus->setColor("red");
        $manager->persist($rejectedStatus);

        $manager->flush();
    }
}
