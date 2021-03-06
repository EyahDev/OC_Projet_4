<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class billetterieControllerTest extends WebTestCase
{
    public function testRouteHomepage()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la route
        $crawler = $client->request('GET', '/');

        // Vérification si on a un retour 200 concernant la route et si il possède bien le texte de présentation de la page
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('html:contains("Indiquez la date de votre visite, votre horaire d\'accès et le nombre de billets")')->count());

    }

    public function testRouteCoordonnees()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la route
        $crawler = $client->request('GET', '/vos-billets');

        // Vérification si on a un retour 302
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Suivi de redirection
        $crawler = $client->followRedirect();

        // Vérification si le retour est bien 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test si le texte de la homepage est présent
        $this->assertSame(1, $crawler->filter('html:contains("Indiquez la date de votre visite, votre horaire d\'accès et le nombre de billets")')->count());

    }

    public function testRouteRecap()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la route
        $crawler = $client->request('GET', '/recapitulatif-et-paiement');

        // Vérification si on a un retour 302
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Suivi de redirection
        $crawler = $client->followRedirect();

        // Vérification si le retour est bien 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test si le texte de la homepage est présent
        $this->assertSame(1, $crawler->filter('html:contains("Indiquez la date de votre visite, votre horaire d\'accès et le nombre de billets")')->count());

    }

    public function testRoutePaiement()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la route
        $crawler = $client->request('GET', '/paiement');

        // Vérification si on a un retour 302
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Suivi de redirection
        $crawler = $client->followRedirect();

        // Vérification si le retour est bien 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test si le texte de la homepage est présent
        $this->assertSame(1, $crawler->filter('html:contains("Indiquez la date de votre visite, votre horaire d\'accès et le nombre de billets")')->count());

    }

    public function testRouteConfirmation()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la route
        $crawler = $client->request('GET', '/confirmation');

        // Vérification si on a un retour 302
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Suivi de redirection
        $crawler = $client->followRedirect();

        // Vérification si le retour est bien 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test si le texte de la homepage est présent
        $this->assertSame(1, $crawler->filter('html:contains("Indiquez la date de votre visite, votre horaire d\'accès et le nombre de billets")')->count());

    }

    public function testParcousBeforePayment()
    {
        // Création d'un client
        $client = self::createClient();

        // Définition de la premiere route
        $crawler = $client->request('GET', '/');

        /* Step 1 */

        // Selection du formulaire de l'étape 2 lié au bouton Suivant
        $formStep1 = $crawler->selectButton('Suivant')->form();

        // Remplissage du formulaire
        $formStep1['order_customer_first_step[email][first]'] = 'adrien.desmet@hotmail.com';
        $formStep1['order_customer_first_step[email][second]'] = 'adrien.desmet@hotmail.com';
        $formStep1['order_customer_first_step[nbTickets]'] = 3;
        $formStep1['order_customer_first_step[visitDate]'] = '2017-09-28';
        $formStep1['order_customer_first_step[duration]'] = 'demi-journée';

        // Envoi du formulaire
        $client->submit($formStep1);

        // Suivi de la rédirection
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Renseignez les informations nominatives de chaque billet (nom, prénom, date de naissance, nationalité)")')->count());

        /* Step 2 */

        // Selection du formulaire de l'étape 2 lié au bouton Suivant
        $formStep2 = $crawler->selectButton('Suivant')->form();

        // Remplissage du formulaire
        $formStep2['order_customer_second_step[tickets][0][name]'] = 'Adrien';
        $formStep2['order_customer_second_step[tickets][0][lastName]'] = 'Desmet';
        $formStep2['order_customer_second_step[tickets][0][age]'] = '23/05/1986';
        $formStep2['order_customer_second_step[tickets][0][country]'] = 'FR';
        $formStep2['order_customer_second_step[tickets][0][reduced_price]'] = 1;

        $formStep2['order_customer_second_step[tickets][1][name]'] = 'Sophia';
        $formStep2['order_customer_second_step[tickets][1][lastName]'] = 'Blithikiotis';
        $formStep2['order_customer_second_step[tickets][1][age]'] = '04/09/1990';
        $formStep2['order_customer_second_step[tickets][1][country]'] = 'FR';

        $formStep2['order_customer_second_step[tickets][2][name]'] = 'Maden';
        $formStep2['order_customer_second_step[tickets][2][lastName]'] = 'Desmet';
        $formStep2['order_customer_second_step[tickets][2][age]'] = '18/01/2016';
        $formStep2['order_customer_second_step[tickets][2][country]'] = 'FR';

        // Envoi du formulaire
        $client->submit($formStep2);

        // Suivi de la rédirection
        $crawler = $client->followRedirect();


        $this->assertSame(1, $crawler->filter('html:contains("Récapitulatif et paiement de votre réservation")')->count());
    }
}
