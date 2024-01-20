<?php

namespace App\Controller;

use App\Form\QuoteSearchType;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

#[Route('/quotes', name: 'quote_')]
class QuoteController extends AbstractController
{
    #[Route('', name: 'index', methods: "get")]
    public function index(
        QuoteRepository $quoteRepository,
        #[MapQueryParameter] ?array $status,
        #[MapQueryParameter] ?string $text,
    ): Response {
        $form = $this->createForm(QuoteSearchType::class);

        if ((!isset($status) || count($status) == 0) && (!isset($text)  || strlen($text) == 0)) {
            $quotes = $quoteRepository->findAll();
        } else {
            $quotes = $quoteRepository->findBySearch([
                "status" => $status,
                "text" => $text,
            ]);
        }

        return $this->render('quote/index.html.twig', [
            'quotes' => $quotes,
            "searchForm" => $form
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        return $this->render('quote/new.html.twig', [
            'controller_name' => 'QuoteController',
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(): Response
    {
        return $this->render('quote/show.html.twig', [
            'controller_name' => 'QuoteController',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function update(): Response
    {
        return $this->render('quote/edit.html.twig', [
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
