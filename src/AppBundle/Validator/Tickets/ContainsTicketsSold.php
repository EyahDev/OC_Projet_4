<?php

namespace AppBundle\Validator\Tickets;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsTicketsSold extends Constraint
{
    public $message = "validator.step.1.visit.date.tickets";

    public function validatedBy()
    {
        return ContainsTicketsSoldValidator::class;
    }
}
