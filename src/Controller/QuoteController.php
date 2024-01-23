<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteCreateType;
use App\Form\QuoteSearchType;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quote = new Quote;

        $form = $this->createForm(QuoteCreateType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //gerer le company et le quote number
            $entityManager->persist($quote);
            $entityManager->flush();


            return $this->redirectToRoute('todo'); //todo: mettre la route des devis
        }

        //Si formulaire non valide 
        return $this->redirectToRoute('home'); //todo: verifier que route home existe
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
