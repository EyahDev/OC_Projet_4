<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateCloseHours21h30 extends Constraint {

    public $message = "Le musée est actuellement fermée, veuillez selectionnez une date à partir de demain";

    public function validatedBy()
    {
        return ContainsVisitDateCloseHours21h30Validator::class;
    }

}