<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateFerie extends Constraint {

    public $message = "La vente de billets ne se fera qu'au gichet pour la date que vous avez choisi, veuillez choisir une autre date";

    public function validatedBy()
    {
        return ContainsVisitDateFerieValidator::class;
    }

}