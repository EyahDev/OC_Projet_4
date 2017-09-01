<?php

namespace AppBundle\Validator\VisitDate;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsVisitDateDimanche extends Constraint {

    public $message = "La vente de billet est désactivé le dimanche, veuillez choisir une autre date ou vous déplacer directement au musée.";

    public function validatedBy()
    {
        return ContainsVisitDateDimancheValidator::class;
    }

}