<?php

namespace AppBundle\Validator\Tickets;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsTicketsSoldValidator extends ConstraintValidator {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        // Mise à 0 du nombre de billets total de la journée
        $tickets = 0;

        // Récupération du nombre de tickets vendu dans la journée selectionné
        $orderCustomers = $this->em->getRepository('AppBundle:OrderCustomer')->findBy(array('visitDate' => $value ));

        // Parcours des commandes de la base de données et ajout du nombre de billets demandés au total
        foreach($orderCustomers as $order) {
            $tickets = $tickets + $order->getNbTickets();
        }

        // Vérification si le nombre de billets vendu est supérieur ou égal à 1000
        if ($tickets >= 1000) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
