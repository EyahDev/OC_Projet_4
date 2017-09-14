<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitCloseFeriesValidator extends ConstraintValidator {

    protected static function getHolidays($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y'));
        }

        $holidays = array(
            // These days have a fixed date
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 12, 25, $year),  // Noel
        );

        sort($holidays);

        return $holidays;
    }

    public function validate($value, Constraint $constraint)
    {
        // Récupération de la date de visite avec une conversion en timestamp
        $visitDate = $value;
        $visitDate = $visitDate->getTimestamp();

        // Récupération de l'année de la date
        $years = $value->format('Y');

        foreach (self::getHolidays($years) as $holiday) {
            // Vérification si la date de visite n'est pas un jour férié
            if ($visitDate == $holiday) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
