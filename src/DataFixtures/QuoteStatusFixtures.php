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
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Envoyé");
        $status->setName("sent");
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Validé");
        $status->setName("accepted");
        $manager->persist($status);

        $status = new QuoteStatus();
        $status->setDisplayName("Refusé");
        $status->setName("refused");
        $manager->persist($status);

        $manager->flush();
    }
}
