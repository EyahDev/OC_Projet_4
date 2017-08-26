<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderCustomer;
use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderCustomerSecondType;
use AppBundle\Form\TicketFirstType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
    public function indexAction(Request $request, Session $session)
    {
        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entitée Ticket
        $ticket = new Ticket();
        $order = $session->get('CommandeLouvre');

        $order->addTicket($ticket);

        // Création du formulaire
        $form = $this->get('form.factory')->create(TicketFirstType::class, $ticket);

        $form->handleRequest($request);
        // Lors de la transmission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de la date de visite et transformation en timestamp
            $visitDate = $ticket->getVisitDate();
            $visitDateTS = $visitDate->getTimestamp();

            // Récupération de la date du jour
            $now = new \DateTime(date("Y-m-d"));

            // Récupération du jour de la date choisi
            $visitDateDay = date('w', $visitDateTS);

            // Récupération de l'année en cours
            $year = intval(date('Y'));

            // Récupération des timestamp des jours fériés
            $mai = mktime(0, 0, 0, 5, 1, $year);
            $mai = date_timestamp_set(new \DateTime(), $mai);
            $nov = mktime(0, 0, 0, 11, 1, $year);
            $nov = date_timestamp_set(new \DateTime(), $nov);
            $noel = mktime(0, 0, 0, 12, 25, $year);
            $noel = date_timestamp_set(new \DateTime(), $noel);

            // Vérification si la date de visite n'est pas inférieur à la date du jour
            if ($visitDate >= $now) {

                // Vérification si la date de visite n'est pas un mardi
                if ($visitDateDay != "2") {

                    // Vérification si la date de visite est un dimanche
                    if ($visitDateDay != "0") {

                        // Vérification si la date de visite n'est pas un jour férié
                        if ($visitDate != $mai || $visitDate != $nov || $visitDate != $noel) {

                            // Vérification que le nombre de billets vendu est bien de 1 minimum et maximum 10
                            if ($ticket->getOrderCustomer()->getNbTickets() >= 1 && $ticket->getOrderCustomer()->getNbTickets() <= 10) {

                                // Récupération du nombre de billets vendus dans la journée
                                $nbTicketsJour = $em->getRepository('AppBundle:Ticket')->getTickets($ticket->getVisitDate());

                                // Récupération nombre de billet demandé par le client
                                $nbTickets = $ticket->getOrderCustomer()->getNbTickets();

                                if ($nbTicketsJour + $nbTickets >= 1000) {
                                    // Création d'un message d'erreur flash
                                    $session->getFlashBag()->add('notice', 'Le quota de billets vendus dans la journée été atteint, veillez saisir une autre date');

                                    // Redirection vers la page de la saisie des coordonées
                                    return $this->redirectToRoute('homepage');
                                }

                                // Injection des données pour la sauvegarde
                                $session->set('nbTickets', $ticket->getOrderCustomer()->getNbTickets());
                                $session->set('duration', $ticket->getDuration());
                                $session->set('visitDate', $ticket->getVisitDate());

                                // Rédirection vers la page de la sélection des coordonnées
                                return $this->redirectToRoute('coordonnees');
                            } else {
                                // Création d'un message d'erreur flash
                                $session->getFlashBag()->add('notice', 'Veuillez saisir une nombre de billets valide');

                                // Redirection vers la page de la saisie des coordonées
                                return $this->redirectToRoute('homepage');
                            }

                        } else {
                            // Création d'un message d'erreur flash
                            $session->getFlashBag()->add('notice', 'Le musée du louvre est fermé ce jour férié, veuillez choisir une autre date');

                            // Redirection vers la page de la saisie des coordonées
                            return $this->redirectToRoute('homepage');
                        }
                    } else {
                        // Création d'un message d'erreur flash
                        $session->getFlashBag()->add('notice', 'Le musée du louvre ne vend pas de billet en ligne le dimanche. Vous pouvez toujours acheter vos billets directement au Louvre');

                        // Redirection vers la page de la saisie des coordonées
                        return $this->redirectToRoute('homepage');
                    }

                } else {
                    // Création d'un message d'erreur flash
                    $session->getFlashBag()->add('notice', 'Le musée du louvre est fermé tous les mardi, veuillez choisir une autre date');

                    // Redirection vers la page de la saisie des coordonées
                    return $this->redirectToRoute('homepage');
                }
            } else {
                // Création d'un message d'erreur flash
                $session->getFlashBag()->add('notice', 'La date de visite ne peut être inférieur à la date du jour, veuillez choisir une autre date');

                // Redirection vers la page de la saisie des coordonées
                return $this->redirectToRoute('homepage');
            }
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function coordonneesAction(Request $request, Session $session)
    {

        if ($session->has('nbTickets')) {
            // Accès à l'entityManager
            $em = $this->getDoctrine()->getManager();

            // Création d'une commande
            $order = new OrderCustomer();

            $order->addTicket(new Ticket());
            $order->addTicket(new Ticket());
            $order->addTicket(new Ticket());

            // Création du formulaire
            $form = $this->get('form.factory')->create(OrderCustomerSecondType::class, $order);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                // Récupération du nombre de billets vendus dans la journée
                $nbTicketsJour = $em->getRepository('AppBundle:Ticket')->getTickets($session->get('visitDate'));

                // Récupération nombre de billet demandé par le client
                $nbTickets = $session->get('nbTickets');

                if ($nbTicketsJour + $nbTickets >= 1000) {
                    // Création d'un message d'erreur flash
                    $session->getFlashBag()->add('notice', 'Le quota de billets vendus dans la journée été atteint, veillez saisir une autre date');

                    // Redirection vers la page de la saisie des coordonées
                    return $this->redirectToRoute('homepage');
                }

                foreach ($order->getTickets() as $ticket) {
                    $ticket->setVisitDate($session->get('visitDate'));
                    $ticket->setDuration($session->get('duration'));
                    $ticket->setOrderCustomer($order);
                }

                // Récupération des variables de sessions
                $nbTicketsSession = $session->get('nbTickets');

                $order->setNbTickets($nbTicketsSession);

                // Sauvegarde des données dans une variable de sessions
                $session->set('CommandeLouvre', $order);

                // Rédirection vers la page de la sélection des coordonnées
                return $this->redirectToRoute('recapitulatif');
            }
            // Affiche la vue et les éléments nécessaire
            return $this->render('ticket/coordonnees.html.twig', array(
                'form' => $form->createView()
            ));

        } else {
            // Création d'un message d'erreur flash
            $session->getFlashBag()->add('notice', 'Veuillez remplir les informations suivante avant d\'aller plus loin.');

            // Redirection vers la page de la saisie des coordonées
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatifAction(Session $session)
    {
        if ($session->has('CommandeLouvre')) {
            // Accès à l'entityManager
            $em = $this->getDoctrine()->getManager();

            // Récupération de la variable de sessions
            $order = $session->get('CommandeLouvre');


            // Récupération du tarif en fonction de l'age du titulaire
            $rates = $em->getRepository('AppBundle:Rate');

            // Mise à 0 du total de la commande
            $total = 0;

            // Parcours de la variable de sessions et attribution des valeurs
            foreach ($order->getTickets() as $ticket) {

                if ($ticket->getRate() === true) {
                    // Récupération du prix et du nom du tarif réduit
                    $rate = $rates->findOneBy(array('name' => 'Réduit'));

                    // Injection à la variable de session
                    $ticket->setRate($rate->getName());
                    $ticket->setPrice($rate->getPrice());

                } else {
                    // Calcul de l'âge
                    $now = new \DateTime();
                    $birthdayDate = $ticket->getAge();
                    $age = $now->diff($birthdayDate)->y;

                    // Récupération du tarif adapté
                    $rate = $rates->getPriceAndRate($age);

                    // Injection à la variable de session
                    $ticket->setRate($rate->getName());
                    $ticket->setPrice($rate->getPrice());
                }
                // Calcul du montant total à payer
                $ticketPrice = $ticket->getPrice();
                $total = $total + $ticketPrice;
                $order->setPrice($total);
            };
            return $this->render('ticket/recapitulatif.html.twig');
        } else {
            // Création d'un message d'erreur flash
            $session->getFlashBag()->add('notice', 'Veuillez remplir les informations suivante avant d\'aller plus loin.');

            // Redirection vers la page de la saisie des coordonées
            return $this->redirectToRoute('coordonnees');
        }

    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiementAction(Session $session)
    {
        dump($session->get('CommandeLouvre'));
        return $this->render('ticket/paiement.html.twig');
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(Request $request, Session $session)
    {

        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de la variable de sessions
        $order = $session->get('CommandeLouvre');

        if ($request->isMethod('POST')) {

            // Récupération du token et de l'adresse mail du client
            $token = $request->get('stripeToken');
            $email = $request->get('stripeEmail');

            // Création de la date de commande
            $date = new \DateTime();

            // Mise à jour de la variable de session
            $order->setEmail($email);
            $order->setOrderDate($date);
            $order->setOrderToken($token);

            $em->persist($order);
            $em->flush();

            return $this->render('ticket/confirmation.html.twig');
        }
        return $this->render('ticket/confirmation.html.twig');
    }
}