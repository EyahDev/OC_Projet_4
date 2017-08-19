<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
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
            $nbTicketsJour= $em->getRepository('AppBundle:Ticket')->getTickets();

            // Récupération du nombre billets commandés
            $nbTickets = $form->get('OrderCustomer')->get('nbTickets')->getData();

            // Récupération de la date de la visite et formatage de la date
            $visitDate = $form->get('visitDate')->getData();
            $visitDate = date_format($visitDate,'d-m-Y');

            // Récupération de la durée de la visite
            $duration = $form->get('duration')->getData();

            if ($nbTicketsJour + $nbTickets >= 1000) {
                // Création d'un message d'erreur flash
                $request->getSession()->getFlashBag()->add('notice', 'Le quota de billets vendus dans la journée été atteint, veillez saisir une autre date');
            }

            // Enregistrement des variables de sessions pour la sauvegarde des données
            $this->get('session')->set('nbTickets', $nbTickets);
            $this->get('session')->set('visitDate', $visitDate);
            $this->get('session')->set('duration', $duration);

            // Redirection vers la page de la saisie des coordonées
            return $this->redirectToRoute('coordonnes');
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/coordonnees", name="coordonnes")
     */
    public function coordonneesAction() {
        return $this->render('ticket/coordonnees.html.twig');
    }
}