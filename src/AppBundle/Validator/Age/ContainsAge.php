<?php

namespace AppBundle\Validator\Age;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAge extends Constraint
{
    public $message = "validator.step.2.age.contains";

    public function validatedBy()
    {
        return ContainsAgeValidator::class;
    }
}
