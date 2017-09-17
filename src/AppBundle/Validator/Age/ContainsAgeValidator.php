<?php

namespace AppBundle\Validator\Age;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAgeValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
        // Calcul de l'âge
        $now = new \DateTime();
        $birthdayDate = $value;
        $age = $now->diff($birthdayDate)->y;

        // Si l'âge est supérieur à 120
        if ($age > 120)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}
