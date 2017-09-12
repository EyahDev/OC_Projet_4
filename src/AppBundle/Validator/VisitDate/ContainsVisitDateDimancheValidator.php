<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateDimancheValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
        // Vérification si la date de visite n'est pas un mardi
        if ($value->format('w') == "0") {
           $this->context->buildViolation($constraint->message)
               ->addViolation();
        }
    }
}
