<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use App\Entity\Role;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $companies = $manager->getRepository(Company::class)->findAll();
        $roles = $manager->getRepository(Role::class)->findAll();

        $user = new User();
        $userComptable = new User();
        $userAdmin = new User();

        $user->setCompany($faker->randomElement($companies));
        $user->setEmail('user@user.fr');
        $user->setPassword($this->passwordHasher->hashPassword($user, '12345678'));
        $user->setRole($this->getReference('ROLE_USER'));
        $user->setIsVerified(true);

        $userComptable->setCompany($faker->randomElement($companies));
        $userComptable->setEmail('comptable@comptable.fr');
        $userComptable->setPassword($this->passwordHasher->hashPassword($userComptable, '12345678'));
        $userComptable->setRole($this->getReference('ROLE_COMPTABLE'));
        $userComptable->setIsVerified(true);

        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, '12345678'));
        $userAdmin->setRole($this->getReference('ROLE_ADMIN'));
        $userAdmin->setIsVerified(true);

        $manager->persist($user);
        $manager->persist($userComptable);
        $manager->persist($userAdmin);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
            RoleFixtures::class,
        ];
    }
}
