<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PriceMax extends Constraint
{
    public string $message = 'Le montant maximum ne peut pas être en dessous du montant minimum ({{ compared_value }})';

    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}