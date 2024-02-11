<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class MaxDate extends Constraint
{
    public string $message = 'La date de fin ne peut pas être antérieure à la date de début ({{ compared_value }})';

    public function __construct(string $mode = null, string $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}