<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateMardi extends Constraint {

    public $message = "validator.step.1.visit.date.tuesday";

    public function validatedBy()
    {
        return ContainsVisitDateMardiValidator::class;
    }

}
