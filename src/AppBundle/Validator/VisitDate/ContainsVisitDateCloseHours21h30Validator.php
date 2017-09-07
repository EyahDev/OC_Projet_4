<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateCloseHours21h30Validator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
       // Récupération de l'heure en actuel
        $date = new \DateTime();
        $time = $date->format('H:i');

        if ($value->format('w') == "3" || $value->format('w') == "5") {
            if ($time >= "21:30") {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}