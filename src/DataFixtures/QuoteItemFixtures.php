<?php

namespace App\DataFixtures;

use App\Entity\QuoteItem;
use App\Entity\Service;
use App\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class QuoteItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $quotes = $manager->getRepository(Quote::class)->findAll();

        $services = $manager->getRepository(Service::class)->findAll();

        if (!$quotes || !$services) {
            throw new \Exception('Assurez-vous que ServiceFixtures et QuoteFixtures sont chargés en premier.');
        }

        foreach ($quotes as $quote) {
            //Chaque Quote possède au  minimum un QuoteItem et au maximum cinq
            for ($i = 0; $i < $faker->numberBetween(1, 5); $i++) {
                $quoteItem = new QuoteItem();
                $quoteItem->setQuote($quote);
                $quoteItem->setQuantity($faker->numberBetween(1, 5));
                $quoteItem->setService($faker->randomElement($services));
                $manager->persist($quoteItem);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            QuoteFixtures::class,
            ServiceFixtures::class,
        ];
    }
}
