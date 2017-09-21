<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateCloseHours extends Constraint {

    public $message = "validator.step.1.visit.date.close.hours";

    public function validatedBy()
    {
        return ContainsVisitDateCloseHoursValidator::class;
    }

}
