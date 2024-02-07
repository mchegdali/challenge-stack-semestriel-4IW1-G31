<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;

use App\Form\UserType;


use App\Form\RegistrationFormType;
use App\Form\CompanyUserRegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends AbstractController
{
    #[Route('/user-admin', name: 'app_list_user_admin')]
    public function adminCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // $this->emailVerifier->sendEmailConfirmation(
            //     'app_verify_email',
            //     $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('register@plumbill.fr', 'Plumbill'))
            //         ->to($user->getEmail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
        }

        $loggedInUser = $this->getUser();
        // dd($loggedInUser);
        $userId = $loggedInUser->getId();

        $users = $doctrine->getManager()->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.id != :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }


    #[Route('/request-company-account', name: 'app_list_request_company_account')]
    public function requestCompanyAccount(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response
    {
        $users = $entityManager->getRepository(User::class)->findBy(['isVerified' => false]);

        // dd($users);

        return $this->render('requestCompanyAccount/index.html.twig', [
            'users' => $users,
        ]);

    }


    #[Route('/user', name: 'app_list_user')]
    public function companyCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response
    {
        $loggedInUser = $this->getUser();

        $comptableRole = $entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_COMPTABLE']);

        $existingComptable = $entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.company = :companyName')
            ->andWhere('u.role = :comptableRoleId') // Utiliser l'ID du rôle
            ->setParameter('companyName', $loggedInUser->getCompany())
            ->setParameter('comptableRoleId', $comptableRole->getId()) // Passer l'ID du rôle
            ->getQuery()
            ->getSingleScalarResult();

        // dd($existingComptable);

        // dd($existingComptable);

        // if ($existingComptable) {
        //     // Handle the case where a "comptable" user already exists (e.g., display an error message)
        //     return $this->render('error/comptable_already_exists.html.twig');
        // }


        $user = new User();
        $form = $this->createForm(CompanyUserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCompany($loggedInUser->getCompany());

            $comptableRole = $entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_COMPTABLE']);

            $user->setRole($comptableRole);

            $entityManager->persist($user);
            $entityManager->flush();

            // $this->emailVerifier->sendEmailConfirmation(
            //     'app_verify_email',
            //     $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('register@plumbill.fr', 'Plumbill'))
            //         ->to($user->getEmail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );

            return new RedirectResponse($this->generateUrl('app_list_user'));
        }

        $userId = $loggedInUser->getId();
        $companyName = $loggedInUser->getCompany();


        $users = $doctrine->getManager()->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.id != :userId')
            ->andWhere('u.company = :companyName')
            ->setParameter('userId', $userId)
            ->setParameter('companyName', $companyName)
            ->getQuery()
            ->getResult();

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'existingComptable' => $existingComptable,
        ]);
    }


    #[Route('/user-details/{id}', name: 'app_user_details')]
    public function companyDetails(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $userRepository = $doctrine->getManager()->getRepository(User::class);

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Company not found');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('user/UserDetails.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/user-admin/{id}/delete', name: 'delete_user_admin')]
    public function deleteUser(PersistenceManagerRegistry $doctrine, User $user): Response
    {
        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_list_user_admin');
    }

    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function deleteUserCompany(PersistenceManagerRegistry $doctrine, User $user): Response
    {
        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_list_user');
    }
}
