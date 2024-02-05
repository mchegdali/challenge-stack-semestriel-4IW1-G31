<?php


namespace App\Twig\Components;

use App\Entity\Payment;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class PaymentListItem
{
    public Payment $payment;
}