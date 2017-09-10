<?php

namespace AppBundle\Validator\Duration;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsDurationValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
        // Récupération de l'heure en actuel
        $date = new \DateTime();
        $time = $date->format('H:i');
        $today = $date->setTime(00,00,00);

        // Récupération de la date choisi
        $date = $this->context->getRoot()->getData()->getVisitDate();

        // Vérification si l'heure est supérieure à 14h
        if ($value == 'journée') {
            if ($today == $date && $time >= '14:00') {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}