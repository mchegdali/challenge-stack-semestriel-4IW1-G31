<?php

namespace App\Controller;

use App\Repository\QuoteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuoteController extends AbstractController
{
    #[Route('/quote', name: 'app_quote')]
    public function index(): Response
    {
        return $this->render('quote/index.html.twig', [
            'controller_name' => 'QuoteController',
        ]);
    }

    #[Route('/quoteslist', name: 'quote_list')]
    public function list(QuoteRepository $quoteRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $company = $user->getCompany();
        if (!$company) {
            throw $this->createNotFoundException('Entreprise non trouvée.');
        }

        $quotes = $quoteRepository->findBy(['company' => $company]);

        return $this->render('quote/list.html.twig', [
            'quotes' => $quotes,
        ]);
    }
}
