<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateMardi extends Constraint {

    public $message = "Le musée du Louvre est fermé le mardi, veuillez saisir une autre date";

    public function validatedBy()
    {
        return ContainsVisitDateMardiValidator::class;
    }

}
