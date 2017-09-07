<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderCustomer;
use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderCustomerFirstStepType;
use AppBundle\Form\OrderCustomerSecondStepType;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderManager
{
    const REDUCEDPRICE = 50;
    private $session;
    private $request;
    private $formBuilder;
    private $mailer;
    private $em;

    public function __construct(SessionInterface $session, RequestStack $request, FormFactory $formBuilder,
                                \Swift_Mailer $mailer, EntityManagerInterface $em)
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

    public function secondStepAction() {

        // Récupération de la commande depuis la session
        $order = $this->getOrder();

        //Définition de l'horaires d'accès
        if ($order->getDuration() == 'demi-journée') {
            $order->setAccess('14h');
        } elseif ($order->getDuration() == 'journée') {
            $order->setAccess('9h');
        }

        // Récupération du nombres de billets demandé par l'utilisateur
        $nbTickets = $order->getNbTickets();

        // Création du nombre de billet demandé par l'utilisateur
        for ($i = 1; $i <= $nbTickets; $i++) {

            // Boucle jusqu'a que le nombre de billets demandé correspond au nombre de billets en commande
            while ($nbTickets != count($order->getTickets())) {

                // Si c'est supérieur, création d'un nouveau billet sinon suppression du dernier billet
                if ($nbTickets > count($order->getTickets())) {
                    $order->addTicket(new Ticket());
                } else {
                    $order->removeTicket($order->getTickets()->last());
                }
            }
        }

        // Création du formulaire
        $form = $this->formBuilder->create(OrderCustomerSecondStepType::class, $order);

        // Retourne le formulaire
        return $form;
    }

    public function summaryAction () {

        // Récupération de la variable de session
        $order = $this->getOrder();

        // Récupération du tarif en fonction de l'age du titulaire
        $rates = $this->em->getRepository('AppBundle:Rate');

        // Mise à 0 du total de la commande
        $total = 0;

        // Définition de la valeur de la remise spécial
        if ($order->getDuration() == "demi-journée") {
            $specialRate = 'demi-journée (-50%)';
        } else {
            $specialRate = 'Aucune';
        }

        // Parcours de la variable de sessions et attribution des valeurs
        foreach ($order->getTickets() as $ticket) {

            // Vérification si la coche tarif réduit a été coché
            if ($ticket->getReducedPrice() === true) {

                // Récupération du prix et du nom du tarif réduit
                $rate = $rates->findOneBy(array('name' => 'Réduit'));

                // Ajout d'une réduction de 50% si demi-journée choisi
                if ($order->getDuration() == "demi-journée") {
                    // Calcul de la réduction en utilisant la constante
                    $price = $rate->getPrice() * self::REDUCEDPRICE / 100;
                } else {
                    $price = $rate->getPrice();
                }
                
                // Injection à la variable de session
                $ticket->setRate($rate->getName());
                $ticket->setPrice($price);

            } else {
                // Calcul de l'âge
                $now = new \DateTime();
                $birthdayDate = $ticket->getAge();
                $age = $now->diff($birthdayDate)->y;

                // Récupération du tarif adapté
                $rate = $rates->getPriceAndRate($age);

                // Ajout d'une réduction de 50% si demi-journée choisi
                if ($order->getDuration() == "demi-journée") {
                    // Calcul de la réduction en utilisant la constante
                    $price = $rate->getPrice() * self::REDUCEDPRICE / 100;
                } else {
                    $price = $rate->getPrice();
                }

                // Injection à la variable de session
                $ticket->setRate($rate->getName());
                $ticket->setPrice($price);
            }

            // Calcul du montant total à payer
            $ticketPrice = $ticket->getPrice();
            $total = $total + $ticketPrice;

        }

        $order->setPrice($total);

        return $specialRate;
    }

    public function confirmationAction() {

        // Récupération du token de paiement
        $token = $this->request->get('stripeToken');

        // Mise à jour de la commande avec les données POST
        if ($this->request->isMethod('POST')) {

            // Récupération de la commande en session
            $order = $this->getOrder();

            // Création de la charge dans stripe
            Stripe::setApiKey("sk_test_CEPeRcQzGSnOsmgIG0zAuhxS");
            Charge::create(array(
                "amount" => $order->getPrice()."00",
                "currency" => "eur",
                "source" => $token,
                "description" => "Réservation de" .$order->getPrice(). " billet(s) en" .$order->getDuration(). " horaires d'accès : ".$order->getAccess()."."));

            // Récupération de la commande en session
            $order = $this->getOrder();

            // Création de la date de paiement de la commande
            $date = new \DateTime();

            // Mise à jour de la commande avec la date du paiement et le token
            $order->setOrderDate($date);
            $order->setOrderToken($token);

            // Enregistrement des données
            $this->em->persist($order);

            // Injection en base de données
            $this->em->flush();

            // Destruction de la session en cours
            $this->session->invalidate();
        }

        // Récupération de la commande en base de données grace au token
        $order = $this->em->getRepository('AppBundle:OrderCustomer')->findOneBy(array('orderToken' => $token));

        // Retourne la commande pour l'affichage
        return $order;
    }
}