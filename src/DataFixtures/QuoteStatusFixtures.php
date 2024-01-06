<?php

namespace App\DataFixtures;

use App\Entity\QuoteStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuoteStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $statusNames = ["VALIDATED" => "Validé", "SENT" => "Envoyé", "DRAFT" => "Brouillon"];

        foreach ($statusNames as $key => $value) {
            $status = new QuoteStatus();
            $status->setName($value);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
