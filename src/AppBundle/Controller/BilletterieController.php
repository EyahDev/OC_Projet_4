<?php

namespace AppBundle\Controller;

use AppBundle\Services\OrderManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BilletterieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, OrderManager $orderManager, SessionInterface $session)
    {
        $form = $orderManager->firstStepAction();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Création d'une variable de session
            $session->set('step_1', 'check');

            // Redirige vers la page des coordonnées
            return $this->redirectToRoute('coordonnees');
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/firststep.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/coordonnees", name="coordonnees")
     */
    public function coordonneesAction(Request $request, OrderManager $orderManager,  SessionInterface $session) {

        $form = $orderManager->secondStepAction();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Création d'une variable de session
            $session->set('step_2', 'check');

            // Redirige vers la page des coordonnées
            return $this->redirectToRoute('recapitulatif');

        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/secondstep.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatifAction(OrderManager $orderManager)
    {
        $specialRate = $orderManager->summaryAction();
        return $this->render('ticket/summary.html.twig', array('specialRate' => $specialRate));
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(OrderManager $orderManager, \Swift_Mailer $mailer)
    {
        $order = $orderManager->confirmationAction();

        // Préparation d'un email de confirmation
        $sendMail = (new \Swift_Message("Confirmation de commande"))
            ->setFrom('adrien.desmet@hotmail.fr')
            ->setTo($order->getEmail())
            ->setBody($this->renderView('ticket/email/recapitulatif.html.twig', array(
                'order' => $order,
                'tickets' => $order->getTickets()
            )), 'text/html');

        // Envoi de l'email
        $mailer->send($sendMail);

        return $this->render('ticket/confirmation.html.twig', array('order' => $order));
    }

}