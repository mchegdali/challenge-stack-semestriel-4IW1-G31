<?php

namespace App\Controller;

use App\Entity\Quote;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Form\QuoteCreateType;
use App\Form\QuoteSearchType;
use App\Repository\InvoiceRepository;
use App\Repository\QuoteRepository;
use App\Service\QuoteToInvoiceConverter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/quotes', name: 'quote_')]
#[IsGranted("ROLE_USER")]
class QuoteController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        QuoteRepository    $quoteRepository,
        Request            $request,
        PaginatorInterface $paginator
    ): Response
    {

        $form = $this->createForm(QuoteSearchType::class, null, ["method" => "POST"]);
        $form->handleRequest($request);

        $company = null;

        if ($this->isGranted('ROLE_ADMIN')) {
            $quotes = $quoteRepository->findAll();
        } else {
            $company = $this->getUser()->getCompany();
            if (!$company) {
                throw $this->createNotFoundException('Entreprise non trouvée');
            }
            $quotes = $quoteRepository->findBy(['company' => $company]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $searchResult = $request->request->all("quote_search");

            $isAdmin = $this->isGranted('ROLE_ADMIN') ? true : false;

            $quotes = $quoteRepository->findBySearch($searchResult, $company, $isAdmin);
        }

        $quotes = $paginator->paginate(
            $quotes,
            $request->query->getInt('page', 1),
            20
        );

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

        //Création formulaire création client

        $customer = new Customer();
        $customerForm = $this->createForm(CustomerType::class, $customer);
        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            // Retourne l'ID du nouveau client sous forme de réponse JSON
            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(), // Assurez-vous que votre entité Customer a une méthode getName()
            ]);
        }

        $type = 'new';

        return $this->render('quote/new_edit.html.twig', [
            '_form' => $form,
            'customerForm' => $customerForm->createView(),
            'type' => $type,
            'typeDocument' => 'quote'
        ]);
    }

    /**
     * @Route("/customer/new", name="customer_new", methods={"POST"})
     */
    public function createCustomer(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(),
                'type_create_customer' => '#quote_create_customer'
            ]);
        }

        // Gérer l'échec de la soumission du formulaire si nécessaire
        return new JsonResponse(['error' => 'Invalid _form'], 400);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Quote $quote): Response
    {

        $invoices = $quote->getInvoices();

        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
            'invoices' => $invoices
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Quote $quote, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuoteCreateType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($quote->getQuoteItems() as $item) {
                //calcul de priceIncludingTax pour chaque quoteItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $em->flush();

            return $this->redirectToRoute('quote_show', ['id' => $quote->getId()]);
        }

        $customer = new Customer();
        $customerForm = $this->createForm(CustomerType::class, $customer);
        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $em->persist($customer);
            $em->flush();

            // Retourne l'ID du nouveau client sous forme de réponse JSON
            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(), // Assurez-vous que votre entité Customer a une méthode getName()
            ]);
        }

        $type = 'edit';

        return $this->render('quote/new_edit.html.twig', [
            '_form' => $form->createView(),
            'quote' => $quote,
            'customerForm' => $customerForm->createView(),
            'type' => $type
        ]);
    }

    #[Route('/convert/{id}', name: 'convert')]
    public function convert(Quote $quote, QuoteToInvoiceConverter $quoteToInvoiceConverter): Response
    {

        if (!$quote) {
            $this->addFlash('error', 'Le devis demandé n\'existe pas.');
            return $this->redirectToRoute('default_index');
        }

        $invoice = $quoteToInvoiceConverter->convert($quote);

        $this->addFlash('success', 'La facture créée à partir du devis.');

        return $this->redirectToRoute(
            'invoice_show', ['id' => $invoice->getId()],
        );
    }
}
