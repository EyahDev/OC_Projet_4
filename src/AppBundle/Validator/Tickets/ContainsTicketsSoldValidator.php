<?php

namespace AppBundle\Validator\Tickets;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsTicketsSoldValidator extends ConstraintValidator {

    private $em;
    private $request;

    public function __construct(EntityManagerInterface $em, RequestStack $request) {
        $this->em = $em;
        $this->request = $request;
    }

    public function validate($value, Constraint $constraint)
    {
        // Récupération du nombre de tickets vendu dans la journée selectionné
        $orderCustomers = $this->em->getRepository('AppBundle:OrderCustomer')->findBy(array('visitDate' => $value ));

        $nbTickets = $this->request->getCurrentRequest()->request->get('order_customer_first_step')->get('email');
        $tickets = 0;
        foreach($orderCustomers as $order) {
            $tickets = $tickets + $order->getNbTickets();
        }

        dump($tickets);
        dump($nbTickets);

//        if ($tickets + $nbTickets >= "1000") {
//            $this->context->buildViolation($constraint->message)
//                ->addViolation();
//        }
    }
}