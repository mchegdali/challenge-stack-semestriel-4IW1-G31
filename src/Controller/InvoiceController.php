<?php

namespace App\Controller;

use App\Entity\Invoice;

use DateTimeImmutable;
use App\Form\InvoiceCreateType;
use App\Form\InvoiceSearchType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/invoices', name: 'invoice_')]
class InvoiceController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        InvoiceRepository $invoiceRepository,
        Request         $request
    ): Response
    {
        $form = $this->createForm(InvoiceSearchType::class, null, ["method" => "POST"] );

        if($request->isMethod("POST")) {
            $searchResult = $request->request->all("invoice_search");
            $invoices = $invoiceRepository->findBySearch($searchResult);
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
            $invoices = $invoiceRepository->findBy(['company' => $company]);
        }

        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices,
            "searchForm" => $form->createView()
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice;

        $form = $this->createForm(InvoiceCreateType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $invoice->setCompany($this->getUser()->getCompany());

            
            foreach ($invoice->getInvoiceItems() as $item) {
                //calcul de priceIncludingTax pour chaque invoiceItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $entityManager->persist($invoice);
            $invoice->setInvoiceNumber(1);
            $entityManager->flush();

            //On set le invoicenumber après le flush car avant le flush l'id n'est pas généré
            $uuid = $invoice->getId()->__toString();
            $invoice->setInvoiceNumber(substr($uuid, strrpos($uuid, '-') + 1));

            //on flush de nouveau pour mettre à jour $invoice 
            $entityManager->flush();


            return $this->redirectToRoute('invoice_new'); //todo: mettre la route des devis
        }

        return $this->render('invoice/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(InvoiceCreateType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('invoice_show', ['id' => $invoice->getId()]);
        }

        return $this->render('invoice/edit.html.twig', [
            'form' => $form->createView(),
            'invoice' => $invoice,
        ]);
    }
}
