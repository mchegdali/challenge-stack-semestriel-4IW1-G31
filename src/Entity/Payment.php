<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentStatus $status = null;

    #[ORM\Column]
    private ?float $amountWithoutTax = null;

    #[ORM\Column]
    private ?float $amountWithTax = null;

    #[ORM\Column]
    private ?float $taxAmount = null;


    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $expectedDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $paymentDate = null;

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

    public function getStatus(): ?PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(?PaymentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAmountWithoutTax(): ?float
    {
        return $this->amountWithoutTax;
    }

    public function setAmountWithoutTax(float $amountWithoutTax): static
    {
        $this->amountWithoutTax = $amountWithoutTax;

        return $this;
    }

    public function getAmountWithTax(): ?float
    {
        return $this->amountWithTax;
    }

    public function setAmountWithTax(float $amountWithTax): static
    {
        $this->amountWithTax = $amountWithTax;

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


    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getExpectedDate(): ?DateTimeImmutable
    {
        return $this->expectedDate;
    }

    public function setExpectedDate(DateTimeImmutable $expectedDate): static
    {
        $this->expectedDate = $expectedDate;

        return $this;
    }

    public function getPaymentDate(): ?DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?DateTimeImmutable $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }
}
