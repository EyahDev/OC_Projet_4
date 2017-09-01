<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use AppBundle\Form\OrderCustomerSecondStepType;
use AppBundle\Services\OrderManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BilletterieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, OrderManager $orderManager)
    {
        $form = $orderManager->indexAction();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Redirige vers la page des coordonnées
            return $this->redirectToRoute('coordonnees');
        }

        // Affiche la vue et les éléments nécessaire
        return $this->render('ticket/firststep.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction () {
        return $this->render('ticket/confirmation.html.twig');
    }

    /**
     * @Route("/coordonnees", name="coordonnees")
     */
    public function coordonneesAction(Request $request, Session $session) {

        if ($session->has('CommandeLouvre')) {
            // Récupération de la commande depuis la session
            $order = $session->get('CommandeLouvre');

            // Récupération du nombres de billets demandé par l'utilisateur
            $nbTickets = $order->getNbTickets();

            // Création du nombre de billet demandé par l'utilisateur
            for ($i = 1; $i <= $nbTickets; $i++) {
                // Vérification si le nombre de billet créé correspond au nombre de billet demandé par l'utilisateur
                if (count($order->getTickets()) != $nbTickets) {

                    // Ajout d'un nouveau ticket
                    $order->addTicket(new Ticket());
                }
            }

            // Création du formulaire
            $form = $this->get('form.factory')->create(OrderCustomerSecondStepType::class, $order);

            $form->handleRequest($request);
            // Lors de la transmission du formulaire
            if ($form->isSubmitted() && $form->isValid()) {

                // Redirige vers la page des coordonnées
                return $this->redirectToRoute('recapitulatif');

            }

            // Affiche la vue et les éléments nécessaire
            return $this->render('secondstep.html.twig', array(
                'form' => $form->createView()
            ));

        }
            // Génération du message de notification
            $session->getFlashBag()->add("notice", "Veuillez remplir cette partie avant d'aller plus loin");

            // Rédirection vers la page d'accueil
            return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     */
    public function recapitulatifAction(Request $request, Session $session)
    {
        if ($session->has('CommandeLouvre') && $session->get('CommandeLouvre')->getPrice() != null) {
            // Accès à l'entityManager
            $em = $this->getDoctrine()->getManager();

            // Récupération de la variable de session
            $order = $session->get('CommandeLouvre');

            // Récupération du tarif en fonction de l'age du titulaire
            $rates = $em->getRepository('AppBundle:Rate');

            // Mise à 0 du total de la commande
            $total = 0;

            // Parcours de la variable de sessions et attribution des valeurs
            foreach ($order->getTickets() as $ticket) {

                // Vérification si la coche tarif réduit a été coché
                if ($ticket->getReducedPrice() === true) {

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

            return $this->render('summary.html.twig');
        }

        // Génération du message de notification
        $session->getFlashBag()->add("notice", "Veuillez remplir cette partie avant d'aller plus loin");

        // Rédirection vers la page d'accueil
        return $this->redirectToRoute('coordonnees');

    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmationAction(Request $request, Session $session, \Swift_Mailer $mailer)
    {
        // Accès à l'entityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de la variable de sessions
        $order = $session->get('CommandeLouvre');

        if ($request->isMethod('POST')) {

            // Récupération du token et de l'adresse mail du client
            $token = $request->get('stripeToken');

            // Création de la date de commande
            $date = new \DateTime();

            // Mise à jour de la variable de session avec la date du paiement et le token de paiement
            $order->setOrderDate($date);
            $order->setOrderToken($token);

            // Enregistrement des données
            $em->persist($order);

            // Injection en base de données
            $em->flush();

            // Préparation d'un email de confirmation
            $sendMail = (new \Swift_Message("Confirmation de commande"))
                    ->setFrom('adrien.desmet@hotmail.fr')
                    ->setTo($order->getEmail())
                    ->setBody($this->render('ticket/email/recapitulatif.html.twig', array(
                        'order' => $order,
                        'tickets' => $order->getTickets()
                    )), 'text/html');

            // Envoi de l'email
            $mailer->send($sendMail);

            // Destruction de la session en cours
            $session->invalidate();

            return $this->render('ticket/confirmation.html.twig');
        }

        return $this->render('ticket/confirmation.html.twig');
    }

}