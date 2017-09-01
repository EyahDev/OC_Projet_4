<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateFerie extends Constraint {

    public $message = "Le musée est fermé ce jour férié, veuillez choisir une autre date";

    public function validatedBy()
    {
        return ContainsVisitDateFerieValidator::class;
    }

}