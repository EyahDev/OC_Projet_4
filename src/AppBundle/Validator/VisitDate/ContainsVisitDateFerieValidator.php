<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateFerieValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
        // Récupération de la date de visite
        $visitDate = $value;

        // Récupération de l'année de la visite
        $year = $visitDate->format('Y');

        // Récupération des timestamp des jours fériés
        $mai = mktime(0, 0, 0, 5, 1, $year);
        $mai = date_timestamp_set(new \DateTime(), $mai);
        $nov = mktime(0, 0, 0, 11, 1, $year);
        $nov = date_timestamp_set(new \DateTime(), $nov);
        $noel = mktime(0, 0, 0, 12, 25, $year);
        $noel = date_timestamp_set(new \DateTime(), $noel);

        // Vérification si la date de visite est supérieur à la date du jour
        if ($visitDate > new \DateTime()) {
            // Vérification si la date de visite n'est pas un jour férié
            if ($visitDate == $mai || $visitDate == $nov || $visitDate == $noel) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}