<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateFerie extends Constraint {

    public $message = "validator.step.1.visit.date.ferie";

    public function validatedBy()
    {
        return ContainsVisitDateFerieValidator::class;
    }

}