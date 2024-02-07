<?php

namespace App\Entity;

use App\Repository\InvoiceItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tax $tax = null;

    #[ORM\Column]
    private ?float $priceExcludingTax = null;

    #[ORM\Column]
    private ?float $priceIncludingTax = null;

    #[ORM\Column]
    private ?float $taxAmount = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getPriceExcludingTax(): ?float
    {
        return $this->priceExcludingTax;
    }

    public function setPriceExcludingTax(float $priceExcludingTax): static
    {
        $this->priceExcludingTax = $priceExcludingTax;

        return $this;
    }

    public function getPriceIncludingTax(): ?float
    {
        return $this->priceIncludingTax;
    }

    public function setPriceIncludingTax(float $priceIncludingTax): static
    {
        $this->priceIncludingTax = $priceIncludingTax;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->taxAmount;
    }

    public function setTaxAmount(float $taxAmount): static
    {
        $this->taxAmount = $taxAmount;

        return $this;
    }
}
