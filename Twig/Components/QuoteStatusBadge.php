<?php

namespace App\Twig\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class QuoteStatusBadge
{
    public \App\Entity\QuoteStatus $quoteStatus;
}