<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\Quote;
use App\Repository\InvoiceStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuoteToInvoiceConverter
{
    private EntityManagerInterface $entityManager;
    private InvoiceStatusRepository $invoiceStatusRepository;

    public function __construct(EntityManagerInterface $entityManager, InvoiceStatusRepository $invoiceStatusRepository)
    {
        $this->entityManager = $entityManager;
        $this->invoiceStatusRepository = $invoiceStatusRepository;
    }

    public function convert(Quote $quote): Invoice
    {
        $invoice = new Invoice();

        $invoice->setCustomer($quote->getCustomer());
        $invoice->setCompany($quote->getCompany());

        $invoice->setStatus($this->invoiceStatusRepository->findOneBy(
            ['name' => 'created']
        ));
        $invoice->setInvoiceNumber('todo'); //TODO 
        $invoice->setDueAt((new \DateTimeImmutable())->modify('+30 days'));

        $invoice->setQuote($quote);

        // Convertir et ajouter les éléments
        foreach ($quote->getQuoteItems() as $quoteItem) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->setInvoice($invoice);
            $invoiceItem->setQuantity($quoteItem->getQuantity());
            $invoiceItem->setService($quoteItem->getService());
            $invoiceItem->setTax($quoteItem->getTax());
            $invoiceItem->setPriceExcludingTax($quoteItem->getPriceExcludingTax());
            $invoiceItem->setPriceIncludingTax($quoteItem->getPriceIncludingTax());
            $invoiceItem->setTaxAmount($quoteItem->getTaxAmount());

            $invoice->getInvoiceItems()->add($invoiceItem);
            
            $this->entityManager->persist($invoiceItem);
        }

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $invoice;
    }
}