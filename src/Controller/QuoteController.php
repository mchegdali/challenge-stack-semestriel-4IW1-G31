<?php

namespace App\Controller;

use App\Entity\Quote;

use DateTimeImmutable;
use App\Form\QuoteCreateType;
use App\Form\QuoteSearchType;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/quotes', name: 'quote_')]
class QuoteController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        QuoteRepository $quoteRepository,
        Request         $request
    ): Response
    {
        $form = $this->createForm(QuoteSearchType::class, null, ["method" => "POST"] );

        if($request->isMethod("POST")) {
            $searchResult = $request->request->all("quote_search");
            $quotes = $quoteRepository->findBySearch($searchResult);
        }
        else {
            $user = $this->getUser();
            if (!$user instanceof UserInterface) {
                throw $this->createNotFoundException('Utilisateur non trouvé');
            }
            $company = $user->getCompany();
            if (!$company) {
                throw $this->createNotFoundException('Entreprise non trouvée');
            }
            $quotes = $quoteRepository->findBy(['company' => $company]);
        }

        return $this->render('quote/index.html.twig', [
            'quotes' => $quotes,
            "searchForm" => $form->createView()
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quote = new Quote;

        $form = $this->createForm(QuoteCreateType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quote->setCompany($this->getUser()->getCompany());

            
            foreach ($quote->getQuoteItems() as $item) {
                //calcul de priceIncludingTax pour chaque quoteItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $entityManager->persist($quote);
            $quote->setQuoteNumber(1);
            $entityManager->flush();

            //On set le quotenumber après le flush car avant le flush l'id n'est pas généré
            $uuid = $quote->getId()->__toString();
            $quote->setQuoteNumber(substr($uuid, strrpos($uuid, '-') + 1));

            //on flush de nouveau pour mettre à jour $quote 
            $entityManager->flush();


            return $this->redirectToRoute('quote_new'); //todo: mettre la route des devis
        }

        return $this->render('quote/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Quote $quote): Response
    {
        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
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
