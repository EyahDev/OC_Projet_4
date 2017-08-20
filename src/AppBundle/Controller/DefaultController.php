<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketCoordonneesType;
use AppBundle\Form\TicketVisitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entitée OrderCustomer
        $ticket = new Ticket();

        // Création du formulaire
        $form = $this->createForm(TicketVisitType::class, $ticket);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            // Récupération du nombre de billets vendus dans la journée
            $nbTicketsJour = $em->getRepository('AppBundle:Ticket')->getTickets();

            // Récupération du nombre billets commandés
            $nbTickets = $form->get('OrderCustomer')->get('nbTickets')->getData();

            // Récupération de la date de la visite et formatage de la date
            $visitDate = $form->get('visitDate')->getData();
            $visitDate = date_format($visitDate,'d/m/Y');

            // Récupération de la durée de la visite
            $duration = $form->get('duration')->getData();

            // Préparation de la variable de session
            $userOrder = array(
                'nbTickets' => $nbTickets,
                'ticket' => array(
                    'visitDate' => $visitDate,
                    'duration' => $duration
                ));


            if ($nbTicketsJour + $nbTickets >= 1000) {
                // Création d'un message d'erreur flash
                $request->getSession()->getFlashBag()->add('notice', 'Le quota de billets vendus dans la journée été atteint, veillez saisir une autre date');

                // Redirection vers la page de la saisie des coordonées
                return $this->redirectToRoute('homepage');
            }


            // Enregistrement des variables de sessions pour la sauvegarde des données
            $this->get('session')->set('CommandeLouvre', $userOrder);

            // Redirection vers la page de la saisie des coordonées
            return $this->redirectToRoute('coordonnees');
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/coordonnees", name="coordonnees")
     */
    public function coordonneesAction(Request $request) {

        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entitée Ticket
        $ticket = new Ticket();

        // Création du formulaire
        $form = $this->createForm(TicketCoordonneesType::class, $ticket);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            // Récupération du nombre de billets vendus dans la journée
            $nbTicketsJour = $em->getRepository('AppBundle:Ticket')->getTickets();

            // Récupération du nombres de tickets depuis la session
            $nbTickets =  $this->get('session')->get('CommandeLouvre')['nbTickets'];

            if ($nbTicketsJour + $nbTickets >= 1000) {
                // Création d'un message d'erreur flash
                $request->getSession()->getFlashBag()->add('notice', 'Le quota de billets vendus dans la journée été atteint, veillez saisir une autre date');

                // Redirection vers la page de la saisie des coordonées
                return $this->redirectToRoute('homepage');
            }


            // Récupération des valeur nominative du billet
            $name = $form->get('name')->getData();
            $lastName = $form->get('lastName')->getData();
            $age = $form->get('age')->getData();
            $country = $form->get('country')->getData();
            $rate = $form->get('rate')->getData();

            // Mise à jour du format de la date de naissance
            $age = date_format($age,'d/m/Y');

            // Récupération de la variable de session initial
            $userOrder = $this->get('session')->get('CommandeLouvre');

            // Détermination du tarif et du prix
            if ($rate === false) {

                // transformation de la date en age
                $birthday = (time() - strtotime($age)) /3600/24/365;
                $birthday = floor($birthday);

                // Recherche du tarif adapté à l'âge du client
                $priceAndRate = $em->getRepository('AppBundle:Rate')->getPriceAndRate($birthday);

                // Définition du tarif
                $rate = $priceAndRate->getId();

                // Définition du prix
                $price = $priceAndRate->getPrice();

            } else {
                // Défintion du tarif réduit
                $rate = 5;

                // Définition du price, grace au tarif
                $price = $em->getRepository('AppBundle:Rate')->find($rate)->getPrice();
            }

            // Ajout des valeur à la variable de session
            $userOrder['ticket'] = array_merge($userOrder['ticket'], array(
                'name' => $name,
                'lastName' => $lastName,
                'age' => $age,
                'country' => $country,
                'rate' => $rate,
                'price' => $price
            ));

            // Enregistrement des variables de sessions pour la sauvegarde des données
            $this->get('session')->set('CommandeLouvre', $userOrder);

            // Redirection vers la page de la saisie des coordonées
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
    public function recapitulatifAction() {
        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de tous les tarifs existant
        $rate = $em->getRepository('AppBundle:Rate')->findAll();

        return $this->render('ticket/recapitulatif.html.twig', array('test' => $rate));
    }
}