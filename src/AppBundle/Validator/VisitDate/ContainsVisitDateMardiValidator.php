<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateMardiValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {

        // Récupération de la date de visite et transformation en timestamp
        $visitDate = $value;
        $visitDateTS = $visitDate->getTimestamp();

        // Récupération du jour de la date choisi
        $visitDateDay = date('w', $visitDateTS);

        // Vérification si la date de visite n'est pas un mardi
        if ($visitDateDay == "2") {
           $this->context->buildViolation($constraint->message)
               ->addViolation();
        }
    }
}
