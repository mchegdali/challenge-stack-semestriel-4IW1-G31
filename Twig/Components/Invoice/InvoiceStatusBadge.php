<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Entity\InvoiceStatus;

#[AsTwigComponent]
class InvoiceStatusBadge
{
    public InvoiceStatus $invoiceStatus;
}