<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItem::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $invoiceItems;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InvoiceStatus $status = null;

    #[ORM\Column(length: 255)]
    private ?string $invoiceNumber = null;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: Payment::class)]
    private Collection $payments;


    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(?InvoiceStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getTotalExcludingTax(): float
    {
        $total = 0.0;

        foreach ($this->getInvoiceItems() as $invoiceItem) {
            $total += $invoiceItem->getPriceExcludingTax() * $invoiceItem->getQuantity();
        }

        return $total;
    }

    /**
     * Calculate the tax amount for the invoice.
     *
     * @return float The total tax amount.
     */
    public function getTaxAmount(): float
    {
        $total = 0.0;
        foreach ($this->getInvoiceItems() as $invoiceItem) {
            $total += $invoiceItem->getTaxAmount() * $invoiceItem->getQuantity();
        }
        return $total;
    }

    /**
     * Calculate the total amount including tax for all invoice items.
     *
     * @return float The total amount including tax.
     */
    public function getTotalIncludingTax()
    {
        $total = 0.0;

        foreach ($this->getInvoiceItems() as $invoiceItem) {
            $total += $invoiceItem->getPriceIncludingtax() * $invoiceItem->getQuantity();
        }

        return $total;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }
}
