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

    public function indexAction()
    {
        // Création de l'entitée Order
        $order = $this->createOrder();

        // Création du formulaire
        $form = $this->formBuilder->create(OrderCustomerFirstStepType::class, $order);

        return $form;

//        $form->handleRequest($request);
//        // Lors de la transmission du formulaire
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            // Récupération de la date de visite
//            $visitDate = $order->getVisitDate();
//
//            // Récupération de la date du jour
//            $today = new \DateTime();
//            // Récupération de l'heure actuel
//            $time = $today->format("H:i");
//
//            // Vérification si la date de visite est inférieur à la date du jour
//            if ($visitDate < $today) {
//
//                // Vérification si l'heure actuel est supérieur à 14h
//                if ($time >= "14:00") {
//
//                    // Mise en place du billet demi journée après 14H
//                    if ($order->getDuration() != 'demi-journée')
//                        $order->setDuration('demi-journée');
//
//                    // Génération du message de notification
//                    $session->getFlashBag()->add("notice", "il est plus de 14h, votre billet pour la journée à été remplacé par un billet pour la démi-journée");
//                }
//            }
//
//            // Création de la variable de session
//            $session->set('CommandeLouvre', $order);
//
        }
}