<?php

namespace AppBundle\Validator\VisitDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsVisitDateFerieValidator extends ConstraintValidator {

    protected static function getHolidays($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y'));
        }

        $easterDate  = easter_date($year);
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $holidays = array(
            // These days have a fixed date
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 25, $year),  // Noel

            // These days have a date depending on easter
            mktime(0, 0, 0, $easterMonth, $easterDay + 2,  $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
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
