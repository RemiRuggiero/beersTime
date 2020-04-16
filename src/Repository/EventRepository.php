<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function countIncomingEvent()
    {
        $stmt = $this->createQueryBuilder('e'); // createbuilder : Initialisation du Query Builder = Pour Récup les Intitées SQL
        $stmt->select('count(e.id)');
        $stmt->where('e.startAt > :now'); // pour un parametre on commence par ":"
        $stmt->setParameter( 'now', new \DateTime() );

        return $stmt->getQuery()->getSingleScalarResult();
    }

    public function searchByName( $query ) // Pour chercher dans la barre de recherche
    {
        $stmt = $this->createQueryBuilder( 'e' );
        $stmt->where( 'e.name LIKE :DEZER' );
        $stmt->setParameter( 'DEZER', '%' . $query . '%' );

        return $stmt->getQuery()->getResult();
    }

    public function getRandom() // Trouver l'inspiration
    {
        $stmt = $this->createQueryBuilder('e');
        $stmt->select('e.id');
        // TODO 
        // Installer https://github.com/beberlei/DoctrineExtensions
        $stmt->where('e.endAt > NOW()');
        $stmt->orderBy( 'RAND()' );
        $stmt->setMaxResults( 1 );
        // Ajouter une séléction aléatoire 
        return $stmt->getQuery()->getSingleScalarResult();
    }
    

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
