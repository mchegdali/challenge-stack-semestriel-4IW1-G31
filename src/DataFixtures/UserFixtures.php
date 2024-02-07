<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $companies = $manager->getRepository(Company::class)->findAll();

        $user = new User();
        $userComptable = new User();
        $userAdmin = new User();

        $user->setCompany($faker->randomElement($companies));
        $user->setEmail('user@user.fr');
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->lastName());
        $user->setPassword($this->passwordHasher->hashPassword($user, '12345678'));
        $user->setRole($this->getReference('ROLE_USER'));
        $user->setIsVerified(true);

        $userComptable->setCompany($faker->randomElement($companies));
        $userComptable->setEmail('comptable@comptable.fr');
        $userComptable->setFirstName($faker->firstName());
        $userComptable->setLastName($faker->lastName());
        $userComptable->setPassword($this->passwordHasher->hashPassword($userComptable, '12345678'));
        $userComptable->setRole($this->getReference('ROLE_COMPTABLE'));
        $userComptable->setIsVerified(true);

        $userAdmin->setEmail('admin@admin.fr');
        $userAdmin->setFirstName($faker->firstName());
        $userAdmin->setLastName($faker->lastName());
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
