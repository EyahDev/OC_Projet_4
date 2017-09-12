<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateCloseHours extends Constraint {

    public $message = "Le musée est actuellement fermé, veuillez selectionnez une autre date";

    public function validatedBy()
    {
        return ContainsVisitDateCloseHoursValidator::class;
    }

}
