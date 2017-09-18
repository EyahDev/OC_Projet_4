<?php

namespace Tests\AppBundle\Services;

use AppBundle\Services\PricesManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class PriceManagerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testGetPrices()
    {
        //Chargement du PriceManager
        $pricesManager = new PricesManager($this->em);

        // Test du tarif normal
        $birthday = new \DateTime('1986-05-23');
        $rateNormal = $pricesManager->getPrice($birthday, false);
        $this->assertSame(array('price' => 16, 'name' => 'Normal'), $rateNormal);

        // Test du tarif sénior
        $birthday = new \DateTime('1944-01-18');
        $rateSenior = $pricesManager->getPrice($birthday, false);
        $this->assertSame(array('price' => 12, 'name' => 'Sénior'), $rateSenior);

        // Test du tarif réduit
        $birthday = new \DateTime('1990-09-04');
        $rateReduit = $pricesManager->getPrice($birthday, true);
        $this->assertSame(array('price' => 10, 'name' => 'Réduit'), $rateReduit);

        // Test du tarif enfant
        $birthday = new \DateTime('2010-01-18');
        $rateEnfant = $pricesManager->getPrice($birthday, false);
        $this->assertSame(array('price' => 8, 'name' => 'Enfant'), $rateEnfant);

        // Test du tarif enfant mais avec tarif réduit de coché
        $birthday = new \DateTime('2010-01-18');
        $rateEnfant = $pricesManager->getPrice($birthday, true);
        $this->assertSame(array('price' => 8, 'name' => 'Enfant'), $rateEnfant);

        // Test du tarif gratuit
        $birthday = new \DateTime('2016-01-18');
        $rateGratuit = $pricesManager->getPrice($birthday, false);
        $this->assertSame(array('price' => 0, 'name' => 'Gratuit'), $rateGratuit);

        // Test du tarif gratuit mais avec tarif réduit de coché
        $birthday = new \DateTime('2016-01-18');
        $rateGratuit = $pricesManager->getPrice($birthday, true);
        $this->assertSame(array('price' => 0, 'name' => 'Gratuit'), $rateGratuit);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
