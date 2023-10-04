<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findAllBook(): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
            ;

    }

    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.titre LIKE :val')
            ->setParameter('val', "%$keyword%")
            ->orderBy('t.titre', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     * This method finds books write by an autor
     * @param int $auteurid The id of the auteur for which we want to find the books
     * @return Livre[] Returns an array to Livre objects
     */
    public function findByAuteur(?int $auteurid): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.auteur = :val')
            ->setParameter('val', $auteurid)
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * This method finds books with a genre
     * @param string $genre The genre of the book for which we want to find the books
     * @return Livre[] Returns an array to Livre objects
     */
    public function findByGenre(?string $genre)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.genres', 'g')
            ->andWhere('g.nom LIKE :nom')
            ->setParameter('nom', "%$genre%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    


//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
