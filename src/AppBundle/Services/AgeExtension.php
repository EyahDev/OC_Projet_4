<?php

namespace AppBundle\Services;


class AgeExtension extends \Twig_Extension
{
    // Création du filtre twig
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('age', array($this, 'ageCalculate'))
        );
    }

    // Fonction de calcul d'âge
    public function ageCalculate(\DateTime $birthDayDate) {
        // Date du jour
        $now = new \DateTime();

        // interval entre la date du jour et la date de naissance
        $interval = $now->diff($birthDayDate);

        // Retourne le nombre d'année d'interval
        return $interval->y;
    }

//    // Définition du nom de l'extension
//    public function getName() {
//        return 'age_extension';
//    }
}