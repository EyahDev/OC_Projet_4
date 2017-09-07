<?php

namespace AppBundle\Validator\Duration;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsDuration extends Constraint
{
    public $message = "Il est plus de 14h, veuillez choisir l'horaire d'accès : demi-journée";

    public function validatedBy()
    {
        return ContainsDurationValidator::class;
    }
}