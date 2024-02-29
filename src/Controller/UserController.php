<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminCreateAccountType;
use App\Form\CompanyCreateAccountType;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\UserRepository;
use App\Utility\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends AbstractController
{
    #[Route('/user-admin', name: 'app_list_user_admin')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function adminCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(AdminCreateAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $randomPassword = PasswordGenerator::generatePassword();

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $randomPassword
                )
            );

            $user->setIsVerified(true);

            $email = (new TemplatedEmail())
                ->from(new Address('challengesemestre@hotmail.com', 'PlumBill'))
                ->to($user->getEmail())
                ->subject('Vos identifiants PlumBill')
                ->htmlTemplate('emails/create-account.html.twig')
                ->context([
                    'lastName' => $user->getLastName(),
                    'firstName' => $user->getFirstName(),
                    'emailAdresse' => $user->getEmail(),
                    'password' => $randomPassword,
                ]);

            $mailer->send($email);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        $loggedInUser = $this->getUser();
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
    #[Security("is_granted('ROLE_ADMIN')")]
    public function requestCompanyAccount(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findBy(['isVerified' => false]);
        return $this->render('requestCompanyAccount/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user', name: 'app_list_user')]
    #[Security("is_granted('ROLE_COMPANY')")]
    public function companyCreateUser(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): Response
    {
        $loggedInUser = $this->getUser();

        $existingComptable = $userRepository
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.company = :companyName')
            ->setParameter('companyName', $loggedInUser->getCompany())
            ->getQuery()
            ->getResult();

        $user = new User();
        $form = $this->createForm(CompanyCreateAccountType::class, $user);
        $form->handleRequest($request);

        $randomPassword = PasswordGenerator::generatePassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $randomPassword
                )
            );

            $user->setIsVerified(true);

            $email = (new TemplatedEmail())
                ->from(new Address('challengesemestre@hotmail.com', 'PlumBill'))
                ->to($user->getEmail())
                ->subject('Vos identifiants PlumBill')
                ->htmlTemplate('emails/create-account.html.twig')
                ->context([
                    'lastName' => $user->getLastName(),
                    'firstName' => $user->getFirstName(),
                    'emailAdresse' => $user->getEmail(),
                    'password' => $randomPassword,
                ]);

            $mailer->send($email);

            $user->setCompany($loggedInUser->getCompany());

            $user->setRoles(['ROLE_COMPTABLE']);

            $entityManager->persist($user);
            $entityManager->flush();

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
    #[Security("is_granted('ROLE_COMPANY')")]
    public function companyDetails(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $userRepository = $doctrine->getManager()->getRepository(User::class);

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Company not found');
        }

        $form = $this->createForm(AdminCreateAccountType::class, $user);

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
    #[Security("is_granted('ROLE_ADMIN')")]
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

    #[Route('/request-company-account/{id}/delete', name: 'delete_request_company_account')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function deleteRequestCompanyAccount(PersistenceManagerRegistry $doctrine, User $user, MailerInterface $mailer): Response
    {
        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('challengesemestre@hotmail.com', 'PlumBill'))
            ->to($user->getEmail())
            ->subject('Information concernant votre demande de compte entreprise')
            ->htmlTemplate('emails/refuse-request-company-account.html.twig')
            ->context([
                'lastName' => $user->getLastName(),
                'firstName' => $user->getFirstName(),
                'company' => $user->getCompany()->getName(),
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('app_list_request_company_account');
    }

    #[Route('/request-company-account/{id}/accept', name: 'accept_request_company_account')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function acceptRequestCompanyAccount(PersistenceManagerRegistry $doctrine, User $user, MailerInterface $mailer): Response
    {
        $user->setIsVerified(true);

        $em = $doctrine->getManager();

        $em->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('challengesemestre@hotmail.com', 'PlumBill'))
            ->to($user->getEmail())
            ->subject('Compte confirmer')
            ->htmlTemplate('emails/confirm-request-company-account.html.twig')
            ->context([
                'lastName' => $user->getLastName(),
                'firstName' => $user->getFirstName(),
                'company' => $user->getCompany()->getName(),
            ]);

        $mailer->send($email);

        return $this->redirectToRoute('app_list_request_company_account');
    }
}
