<?php

namespace AppBundle\Validator\Tickets;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsTicketsSold extends Constraint
{
    public $message = "Le musée a depassé sa capacité d'accueil pour la journée, veuillez choisir une autre date";

    public function validatedBy()
    {
        return ContainsTicketsSoldValidator::class;
    }
}
