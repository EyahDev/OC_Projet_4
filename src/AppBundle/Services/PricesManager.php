<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManagerInterface;

class PricesManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getPrice($birthday, $reducedPrice)
    {
        // Calcul de l'âge
        $now = new \DateTime();
        $birthdayDate = $birthday;
        $age = $now->diff($birthdayDate)->y;

        // Récupération de tous les tarifs existant
        $rates = $this->em->getRepository('AppBundle:Rate');

        // Récupération du prix et du nom du tarif réduit
        $ReducedPriceRate = $rates->findOneBy(array('name' => 'Réduit'));

        // Récupération du tarif adapté
        $bestRate = $rates->getPriceAndRate($age);

        // Retourne le prix le plus logique par rapport à l'age
        if ($reducedPrice)
        {
            if ($bestRate->getPrice() < $ReducedPriceRate->getPrice())
            {
                return array('price' => $bestRate->getPrice(), 'name' => $bestRate->getName());
            }
            return array('price' => $ReducedPriceRate->getPrice(), 'name' => $ReducedPriceRate->getName());
        }
        return array('price' => $bestRate->getPrice(), 'name' => $bestRate->getName());
    }
}
