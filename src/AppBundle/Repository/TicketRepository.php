<?php

namespace AppBundle\Repository;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Récupération du nombre de billets vendus à la date du jour
     */
    public function getTickets($date)
    {
        // Accès au QueryBuilder
        $qb = $this->createQueryBuilder('t');

        // Recherche de billets avec la date du jour concernée
        $qb->where('t.visitDate = :date')->setParameter('date', $date);

        // Récupération des résultats
        $nbTickets = $qb->getQuery()->getResult();

        // Retourne les résultats
        return count($nbTickets);
    }
}
