<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $manager->persist($roleUser);
        $this->setReference('ROLE_USER', $roleUser);

        $roleComptable = new Role();
        $roleComptable->setName('ROLE_COMPTABLE');
        $this->setReference('ROLE_COMPTABLE', $roleComptable);
        $manager->persist($roleComptable);

        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);
        $this->setReference('ROLE_ADMIN', $roleAdmin);

        $manager->flush();
    }
}
