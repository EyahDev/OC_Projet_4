<?php

namespace AppBundle\Services;


use AppBundle\Entity\OrderCustomer;
use AppBundle\Form\OrderCustomerFirstStepType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderManager
{
    private $session;
    private $request;
    private $formBuilder;
    private $mailer;
    private $em;

    public function __construct(SessionInterface $session, RequestStack $request, FormFactory $formBuilder, \Swift_Mailer $mailer, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->request = $request->getCurrentRequest();
        $this->formBuilder = $formBuilder;
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function createOrder()
    {
        if ($this->session->has('CommandeLouvre')) {
            $order = $this->session->get('CommandeLouvre');
        } else {
            $order = new OrderCustomer();
            $this->session->set('CommandeLouvre', $order);
        }

        return $order;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getOrder()
    {
        if ($this->session->has('CommandeLouvre')) {
           return $this->session->get('CommandeLouvre');
        } else {
            throw new \Exception('Pas commande en session');
        }
    }

    public function firstStepAction()
    {
        // Création de l'entitée Order
        $order = $this->createOrder();

        // Création du formulaire
        $form = $this->formBuilder->create(OrderCustomerFirstStepType::class, $order);

        return $form;
    }
}