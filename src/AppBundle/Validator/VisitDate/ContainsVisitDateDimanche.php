<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateDimanche extends Constraint {

    public $message = "validator.step.1.visit.date.sunday";

    public function validatedBy()
    {
        return ContainsVisitDateDimancheValidator::class;
    }

}
