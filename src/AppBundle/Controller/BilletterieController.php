<?php

namespace AppBundle\Controller;

use AppBundle\Services\OrderManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BilletterieController extends Controller
{
    /**
     * @Route("/setlocale/{_locale}", name="setlocale")
     */
    public function setLocaleAction( Request $request)
    {
//        if($language != null)
//        {
//            // On enregistre la langue en session
//            $request->setLocale($language);
//        }

        // on tente de rediriger vers la page d'origine
        $url = $request->headers->get('referer');

        if(empty($url))
        {
            $url = $this->container->get('router')->generate('homepage');
        }

        return new RedirectResponse($url);
    }


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, OrderManager $orderManager)
    {
        // Récupération du formulaire de la première étape
        $form = $orderManager->firstStepAction();

        // Hydratation de la commande avec les valeur du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Création d'une variable de session pour valider la première étape
            $orderManager->stepCheck(1);

            // Redirige vers la page des coordonnées
            return $this->redirectToRoute('vos-billets');
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/firststep.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/vos-billets", name="vos-billets")
     */
    public function coordonneesAction(Request $request, OrderManager $orderManager, SessionInterface $session) {

        // Vérification si la session a expiré
        if (!$session->isStarted()) {
            // Génération d'un message d'erreur
            $error = $session->getFlashBag()->add('notice', 'session_expire.text');

            // Redirection vers la première étape
            return $this->redirectToRoute('homepage', array('error' => $error));
        }

        // Vérification si l'étape 1 est passé avec succès
        if ($orderManager->getStepIsCheck(1)) {

            // Récupération du formulaire
            $form = $orderManager->secondStepAction();

            // Hydratation de la commande avec les valeur du formulaire
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // Création d'une variable de session
                $orderManager->stepCheck(2);

                // Redirige vers la page des coordonnées
                return $this->redirectToRoute('recapitulatif');
            }
            // Affiche la vue et les éléments nécessaire
            return $this->render('ticket/secondstep.html.twig', array(
                'form' => $form->createView()));
        }
        // Redirection vers la première étape
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/recapitulatif-et-paiement", name="recapitulatif")
     */
    public function recapitulatifAction(OrderManager $orderManager, SessionInterface $session) {

        // Vérification si la session a expiré
        if (!$session->isStarted()) {
            // Génération d'un message d'erreur
            $error = $session->getFlashBag()->add('notice', 'session_expire.text');

            // Redirection vers la première étape
            return $this->redirectToRoute('homepage', array('error' => $error));
        }

        // Vérification si l'étape 2 est passé avec succès
        if ($orderManager->getStepIsCheck(2)) {

            // Mise en place des tarifs associés à l'âge du titulaire et récupération du tarif spéciale si il existe
            $specialRate = $orderManager->summaryAction();

            if ($specialRate == 'error')
            {
                // Génération d'un message d'erreur
                $session->getFlashBag()->add('notice', 'Un enfant de moins de 12 ans doit être accompagné d\'un adulte');

                // Redirection vers la page coordonnees
                return $this->redirectToRoute('vos-billets');
            }

            // Affiche la vue et les éléments nécessaire
            return $this->render('ticket/summary.html.twig', array('specialRate' => $specialRate));
        }

        // Redirection vers la page coordonnees
        return $this->redirectToRoute('vos-billets');
    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiementAction(OrderManager $orderManager, SessionInterface $session) {

        // Vérification si la session a expiré
        if (!$session->isStarted()) {
            // Génération d'un message d'erreur
            $error = $session->getFlashBag()->add('notice', 'session_expire.text');

            // Redirection vers la première étape
            return $this->redirectToRoute('homepage', array('error' => $error));
        }

        // Vérification si l'étape 3 est passé avec succès
        if ($orderManager->getStepIsCheck(3)) {

            // Lancement du paiement
            $paiement = $orderManager->paiement();

            // Vérification si le paiement s'est bien déroulé
            if ($paiement) {

                // Enregistrement de la commande et envoi du mail de confirmation
                $orderManager->enregistrement();

                // Redirection vers la confirmation
                return $this->redirectToRoute('confirmation');
            }

            // Génération d'un message d'erreur
            $error = $session->getFlashBag()->add('notice', 'appbundle.step.3.stripe.error');

            // Redirection vers la page de paiement
            return $this->redirectToRoute('recapitulatif', array('error' => $error));
        }
        // Redirection vers la page de paiement
        return $this->redirectToRoute('recapitulatif');
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(OrderManager $orderManager, SessionInterface  $session)
    {
        // Vérification si la session a expiré
        if (!$session->isStarted()) {
            // Génération d'un message d'erreur
            $error = $session->getFlashBag()->add('notice', 'session_expire.text');

            // Redirection vers la première étape
            return $this->redirectToRoute('homepage', array('error' => $error));
        }

        // Vérification si l'étape 4 est passé avec succès ou que toutes les étapes sont validés
        if ($orderManager->getStepIsCheck(4) || $orderManager->getStepIsCheck("all")) {

            // Récupération des informations importante de la commande
            $order = $orderManager->confirmationAction();

            // Affichage de la vue confirmation
            return $this->render('ticket/confirmation.html.twig', array('order' => $order));
        }
        // Redirection vers la page du récapitulatif
        return $this->redirectToRoute('recapitulatif');
    }

    /**
     * @Route("/mail/{token}", name="mail")
     */
    public function affichageMailAction(OrderManager $orderManager, $token) {

        // Récupération de la commande lié au token
        $order = $orderManager->mailAction($token);

        if (count($order) === 1) {
            // Affichage de la vue confirmation
            return $this->render('ticket/email/affichage.html.twig', array('order' => $order));
        }

        throw new Exception('La commande n\'existe pas');

    }
}
