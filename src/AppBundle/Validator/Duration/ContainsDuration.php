<?php

namespace AppBundle\Validator\Duration;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsDuration extends Constraint
{
    public $message = "validator.step.1.duration.contains";

    public function validatedBy()
    {
        return ContainsDurationValidator::class;
    }
}
