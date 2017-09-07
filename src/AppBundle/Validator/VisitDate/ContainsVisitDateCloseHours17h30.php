<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateCloseHours17h30 extends Constraint {

    public $message = "Le musée est actuellement fermé, veuillez selectionnez une date à partir de demain";

    public function validatedBy()
    {
        return ContainsVisitDateCloseHours17h30Validator::class;
    }

}