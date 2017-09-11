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
        $session->set('step_all', 'uncheck');
        $session->set('step_1', 'uncheck');
        $session->set('step_2', 'uncheck');
        $session->set('step_3', 'uncheck');
        $session->set('step_4', 'uncheck');

        // Retrait de la variable token si elle existe
        $session->remove('token');

        // Récupération du formulaire de la première étape
        $form = $orderManager->firstStepAction();

        // Hydratation de la commande avec les valeur du formulaire
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

        if ($session->get('step_1') == "check") {
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
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatifAction(OrderManager $orderManager, SessionInterface $session) {
        // uncheck de toutes les variables d'étapes
        $session->set('step_3', 'uncheck');
        $session->set('step_4', 'uncheck');

        if ($session->get('step_2') == "check") {

            $specialRate = $orderManager->summaryAction();

            $session->set('step_3', 'check');

            return $this->render('ticket/summary.html.twig', array('specialRate' => $specialRate));
        }
        return $this->redirectToRoute('coordonnees');
    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiementAction(OrderManager $orderManager, SessionInterface $session, \Swift_Mailer $mailer) {

        // uncheck de toutes les variables d'étapes
        $session->set('step_4', "uncheck");

        if ($session->get('step_3') == "check") {
            // Lancement du paiement
            $paiement = $orderManager->paiement();

            // Vérification si le paiement s'est bien déroulé
            if ($paiement == 'check') {

                // Récupération de la commande en session
                $order = $session->get('CommandeLouvre');

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

                // Enregistrement de la commande
                $orderManager->enregistrement();

                // Check de l'étape 3
                $session->set('step_4', "check");

                return $this->redirectToRoute('confirmation');
            }

            $error = $session->getFlashBag()->add('Une erreur s\'est produit avec le paiement, veuillez réessayer. Si le problème persiste, merci de nous contacter');

            return $this->redirectToRoute('confirmation', array('error' => $error));
        }

        return $this->redirectToRoute('recapitulatif');
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(OrderManager $orderManager, SessionInterface $session)
    {
        if ($session->get('step_4') == "check" || $session->get('step_all') == "check") {
            // Récupération des informations importante de la commande
            $order = $orderManager->confirmationAction();

            $session->set('step_all', 'check');
            $session->set('step_1', 'uncheck');
            $session->set('step_2', 'uncheck');
            $session->set('step_3', 'uncheck');
            $session->set('step_4', 'uncheck');

            return $this->render('ticket/confirmation.html.twig', array('order' => $order));
        }
        return $this->redirectToRoute('recapitulatif');
    }

}