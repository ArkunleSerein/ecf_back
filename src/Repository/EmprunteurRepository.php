<?php

namespace App\Repository;

use Datetime;
use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }


    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }



    /**
     * This method find all emprunteur containing one word
     * @param string $keyword The word to search for
     * @return Emprunteur[] Returns an array of emprunteur objects
     */
    public function findByEmprunteur(?string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :val')
            ->orWhere('e.prenom LIKE :val')
            ->setParameter('val', "%$keyword%")
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * This method find all emprunteur containing one word
     * @param string $keyword The word to search for
     * @return Emprunteur[] Returns an array of emprunteur objects
     */
    public function findByPhone(?int $tel): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.tel LIKE :val')
            ->setParameter('val', "%$tel%")
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * This method find all emprunteur created before one date
     * @param DateTime $date The date to compare with
     * @return Emprunteur[] Returns an array of emprunteur objects
     */
    public function createAt(DateTime $date): array
    {
        return $this->createQueryBuilder('e')
            ->select('e') 
            ->where('e.createdAt < :date') 
            ->setParameter('date', $date)
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Emprunteur[] Returns an array of Emprunteur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Emprunteur
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
