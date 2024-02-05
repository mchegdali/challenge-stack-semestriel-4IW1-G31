<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceCreateType;
use App\Form\InvoiceFormType;
use App\Form\InvoiceSearchType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class InvoiceController extends AbstractController
{

    #[Route('/invoice', name: 'create_invoice')]
    public function createInvoice(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceFormType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($invoice);
            $em->flush();
        }

        $invoices = $doctrine->getManager()->getRepository(Invoice::class)->findAll();

        return $this->render('invoice/invoice.html.twig', [
            'form' => $form->createView(),
            'invoices' => $invoices,
        ]);
    }

    #[Route('/invoice/{id}/delete', name: 'delete_invoice')]
    public function deleteInvoice(Request $request, PersistenceManagerRegistry $doctrine, Invoice $invoice): Response
    {
        $em = $doctrine->getManager();
        $em->remove($invoice);
        $em->flush();
    
        return $this->redirectToRoute('create_invoice');
    }

    #[Route('/invoice/{id}/update', name: 'update_invoice')]
    public function updateInvoice(Request $request, PersistenceManagerRegistry $doctrine, Invoice $invoice): Response
    {
        $form = $this->createForm(InvoiceFormType::class, $invoice);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('create_invoice');
        }
    
        return $this->render('default/UpdateTax.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   /* #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(InvoiceRepository $invoiceRepository, Request $request): Response
    {
        $form = $this->createForm(InvoiceSearchType::class, null, ["method" => "POST"]);

        if ($request->isMethod("POST")) {
            $searchResult = $request->request->all("invoice_search");
            $invoices = $invoiceRepository->findBySearch($searchResult);
        } else {
            $invoices = $invoiceRepository->findAll();
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

            $invoice->setCreatedAt(new \DateTimeImmutable('now'));
            $invoice->setCompany($this->getUser()->getCompany());

            $entityManager->persist($invoice);
            $entityManager->flush();

            // Générer un numéro de facture basé sur l'ID de facture
            $invoiceNumber = 'INV-' . str_pad($invoice->getId(), 6, '0', STR_PAD_LEFT);
            $invoice->setInvoiceNumber($invoiceNumber);

            $entityManager->flush();

            return $this->redirectToRoute('invoice_index');
        }

        return $this->render('invoice/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    } */

}