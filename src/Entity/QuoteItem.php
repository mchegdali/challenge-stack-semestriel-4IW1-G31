<?php

namespace App\Entity;

use App\Repository\QuoteItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuoteItemRepository::class)]
class QuoteItem
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quoteItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quote $quoteId = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getQuoteId(): ?Quote
    {
        return $this->quoteId;
    }

    public function setQuoteId(?Quote $quoteId): static
    {
        $this->quoteId = $quoteId;

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
}
