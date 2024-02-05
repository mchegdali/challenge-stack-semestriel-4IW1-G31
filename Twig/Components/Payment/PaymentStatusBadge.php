<?php

namespace App\Twig\Components;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Entity\PaymentStatus;

#[AsTwigComponent]
class PaymentStatusBadge
{
    public PaymentStatus $paymentStatus;
}