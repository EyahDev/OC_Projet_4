<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitCloseFeries extends Constraint {

    public $message = "validator.step.1.visit.date.close.feries";

    public function validatedBy()
    {
        return ContainsVisitCloseFeriesValidator::class;
    }

}