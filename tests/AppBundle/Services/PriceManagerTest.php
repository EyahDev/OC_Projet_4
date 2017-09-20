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

    /**
     * @dataProvider getPricesProvider
     */
    public function testGetPrices($date, $reducedPrice, $exPrice, $exName)
    {
        //Chargement du PriceManager
        $pricesManager = new PricesManager($this->em);

        // Test du tarif normal
        $birthday = new \DateTime($date);
        $result = $pricesManager->getPrice($birthday, $reducedPrice);
        $this->assertSame(array('price' => $exPrice, 'name' => $exName), $result);
    }

    public function getPricesProvider()
    {
        return array(
            array('1986-05-23', false, 16, 'Normal'),
            array('1944-01-18', false, 12, 'Sénior'),
            array('1990-09-04', true, 10, 'Réduit'),
            array('2010-01-18', false, 8, 'Enfant'),
            array('2010-01-18', true, 8, 'Enfant'),
            array('2016-01-18', false, 0, 'Gratuit'),
            array('2016-01-18', true, 0, 'Gratuit')
        );
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
