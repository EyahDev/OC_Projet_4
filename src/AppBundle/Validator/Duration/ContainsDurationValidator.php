<?php

namespace AppBundle\Validator\Duration;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsDurationValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
       // Récupération de l'heure en actuel
        $time = new \DateTime();
        $time = $time->format('H:i');

        // Vérification si l'heure est supérieure à 14h
        if ($time >= '14:00') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}