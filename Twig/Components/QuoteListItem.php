<?php


namespace App\Twig\Components;

use App\Entity\Quote;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class QuoteListItem
{
    public Quote $quote;
}