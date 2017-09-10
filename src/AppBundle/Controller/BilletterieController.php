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
        // uncheck de toutes les variables d'étapes
        $session->set('step_1', 'uncheck');
        $session->set('step_2', 'uncheck');
        $session->set('step_3', 'uncheck');
        $session->set('step_4', 'uncheck');

        $form = $orderManager->firstStepAction();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Création d'une variable de session pour valider la première étape
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

        // uncheck de toutes les variables d'étapes
        $session->set('step_2', 'uncheck');
        $session->set('step_3', 'uncheck');
        $session->set('step_4', 'uncheck');

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
    public function recapitulatifAction(OrderManager $orderManager, SessionInterface $session)
    {
        // uncheck de toutes les variables d'étapes
        $session->set('step_3', 'uncheck');
        $session->set('step_4', 'uncheck');

        $specialRate = $orderManager->summaryAction();

        return $this->render('ticket/summary.html.twig', array('specialRate' => $specialRate));
    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiementAction(OrderManager $orderManager, SessionInterface $session, \Swift_Mailer $mailer) {

        $paiement = $orderManager->paiement();
        $order = $session->get('CommandeLouvre');

        if ($paiement == 'check') {

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

            return $this->redirectToRoute('confirmation');
        }

        $error = $session->getFlashBag()->add('Une erreur s\'est produit avec le paiement, veuillez réessayer. Si le problème persiste, merci de nous contacter');

        $this->redirectToRoute('recapitulatif', array('error' => $error));
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(OrderManager $orderManager, SessionInterface $session)
    {
        $order = $orderManager->confirmationAction();

        // Check de l'étape 3
        $session->set('step_3', "check");

        return $this->render('ticket/confirmation.html.twig', array('order' => $order));
    }

}