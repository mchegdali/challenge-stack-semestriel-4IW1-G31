<?php

namespace App\Controller;

use App\Form\QuoteSearchType;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quotes', name: 'quote_')]
class QuoteController extends AbstractController
{
    #[Route('', name: 'index', methods: "get")]
    public function index(QuoteRepository $quoteRepository): Response
    {

        $form = $this->createForm(QuoteSearchType::class);

        return $this->render('quote/index.html.twig', [
            'quotes' => $quoteRepository->findAll(),
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
}
