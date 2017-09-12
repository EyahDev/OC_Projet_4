<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateCloseHoursValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
       // Récupération de l'heure en actuel
        $date = new \DateTime();
        $time = $date->format('H:i');
        $today = $date->setTime(00,00,00);

        if ($value->format('w') != "3" || $value->format('w') != "5") {
            if ($today == $value && $time >= "17:30") {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        } else {
            if ($today == $value && $time >= "21:30") {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
