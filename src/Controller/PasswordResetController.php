<?php

namespace App\Controller;

use App\Entity\PasswordResetToken;
use App\Form\PasswordResetRequestFormType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Repository\PasswordResetTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordResetController extends AbstractController
{

    /**
     * @Route("/password-reset/request", name="app_password_reset_request")
     */
    public function request(Request $request, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(PasswordResetRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tokenString = $tokenGenerator->generateToken();
            $email = $form->get('email')->getData();

            $token = new PasswordResetToken();
            $token->setEmail($email);
            $token->setToken($tokenString);
            $token->setExpiresAt(new \DateTime('+1 hour'));

            $entityManager->persist($token);
            $entityManager->flush();

            // Utilisation de TemplatedEmail pour l'envoi de l'email de réinitialisation
            $email = (new TemplatedEmail())
                ->from('challengesemestre@hotmail.com')
                ->to($token->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('emails/password_reset.html.twig')
                ->context([
                    'token' => $token->getToken(),
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Un e-mail de réinitialisation de mot de passe a été envoyé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }


  /**
 * @Route("/password-reset/reset/{token}", name="app_password_reset")
 */
public function reset($token, Request $request, PasswordResetTokenRepository $tokenRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    $tokenEntity = $tokenRepository->findOneBy(['token' => $token]);

    if (!$tokenEntity || $tokenEntity->getExpiresAt() < new \DateTime()) {
        $this->addFlash('danger', 'Le token de réinitialisation du mot de passe est invalide ou expiré.');
        return $this->redirectToRoute('app_password_reset_request');
    }

    $form = $this->createForm(PasswordResetType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $userRepository->findOneByEmail($tokenEntity->getEmail());
    
        if (!$user) {
            $this->addFlash('danger', 'Aucun utilisateur trouvé pour cet e-mail.');
            return $this->redirectToRoute('app_password_reset_request');
        }
    
        // Hasher le nouveau mot de passe
        $newPassword = $form->get('newPassword')->getData();
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
    
        $entityManager->persist($user);
        $entityManager->flush();
    
        // Supprimer le token de réinitialisation pour éviter sa réutilisation
        $entityManager->remove($tokenEntity);
        $entityManager->flush();
    
        $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('password_reset/reset.html.twig', [
        'resetForm' => $form->createView(),
    ]);
}
}
