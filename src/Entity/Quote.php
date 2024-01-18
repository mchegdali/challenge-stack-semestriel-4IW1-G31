<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\OneToMany(mappedBy: 'quote', targetEntity: QuoteItem::class, orphanRemoval: true)]
    private Collection $quoteItems;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuoteStatus $status = null;


    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(length: 255)]
    private ?string $quoteNumber = null;


    public function __construct()
    {
        $this->quoteItems = new ArrayCollection();
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

    /**
     * @return Collection<int, QuoteItem>
     */
    public function getQuoteItems(): Collection
    {
        return $this->quoteItems;
    }

    public function addQuoteItem(QuoteItem $quoteItem): static
    {
        if (!$this->quoteItems->contains($quoteItem)) {
            $this->quoteItems->add($quoteItem);
            $quoteItem->setQuote($this);
        }

        return $this;
    }

    public function removeQuoteItem(QuoteItem $quoteItem): static
    {
        if ($this->quoteItems->removeElement($quoteItem)) {
            // set the owning side to null (unless already changed)
            if ($quoteItem->getQuote() === $this) {
                $quoteItem->setQuote(null);
            }
        }

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

    public function getStatus(): ?QuoteStatus
    {
        return $this->status;
    }

    public function setStatus(?QuoteStatus $status): static
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

    public function getQuoteNumber(): ?string
    {
        return $this->quoteNumber;
    }

    public function setQuoteNumber(string $quoteNumber): static
    {
        $this->quoteNumber = $quoteNumber;

        return $this;
    }

    public function getTotalExcludingTax(): float
    {
        $total = 0.0;

        foreach ($this->getQuoteItems() as $quoteItem) {
            $total += $quoteItem->getPriceExcludingTax() * $quoteItem->getQuantity();
        }

        return $total;
    }

    /**
     * Calculate the tax amount for the quote.
     *
     * @return float The total tax amount.
     */
    public function getTaxAmount(): float
    {
        $total = 0.0;
        foreach ($this->getQuoteItems() as $quoteItem) {
            $total += $quoteItem->getTaxAmount() * $quoteItem->getQuantity();
        }
        return $total;
    }

    /**
     * Calculate the total amount including tax for all quote items.
     *
     * @return float The total amount including tax.
     */
    public function getTotalIncludingTax()
    {
        $total = 0.0;

        foreach ($this->getQuoteItems() as $quoteItem) {
            $total += $quoteItem->getPriceIncludingtax() * $quoteItem->getQuantity();
        }

        return $total;
    }
}
