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
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, Session $session)
    {
        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entitée Ticket
        $ticket = new Ticket();

        // Création du formulaire
        $form = $this->get('form.factory')->create(TicketFirstType::class, $ticket);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
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
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/coordonnees", name="coordonnees")
     */
    public function coordonneesAction(Request $request, Session $session) {

        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        $order = new OrderCustomer();

        // Création du formulaire
        $form = $this->get('form.factory')->create(OrderCustomerSecondType::class, $order);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
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
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatifAction(Session $session) {

        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de la variable de sessions
        $order = $session->get('CommandeLouvre');



        // Récupération du tarif en fonction de l'age du titulaire
        $rates = $em->getRepository('AppBundle:Rate');

        // Parcours de la variable de sessions et attribution des valeurs
        foreach ($order->getTickets() as $ticket) {

            if ($ticket->getRate()) {
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
        };

        return $this->render('ticket/recapitulatif.html.twig');
    }
}