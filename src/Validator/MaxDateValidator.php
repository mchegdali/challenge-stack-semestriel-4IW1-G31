<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxDateValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param MaxDate $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($value < $this->context->getRoot()->get('minDate')->getData()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ compared_value }}', $this->context->getRoot()->get('minDate')->getData()->format('d/m/Y'))
                ->addViolation();
        }
    }
}
