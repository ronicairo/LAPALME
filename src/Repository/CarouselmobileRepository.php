<?php

namespace App\Repository;

use App\Entity\Carouselmobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carouselmobile>
 *
 * @method Carouselmobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carouselmobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carouselmobile[]    findAll()
 * @method Carouselmobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarouselmobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carouselmobile::class);
    }

    public function save(Carouselmobile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Carouselmobile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllArchived()
    {
        return $this->createQueryBuilder('c') 
        ->where('c.deletedAt IS NOT NULL') 
        ->getQuery() # Permet de récupérer la requête SQL
        ->getResult() # Permet de récupérer els résultats de la requête
        ;
    }

//    /**
//     * @return Carouselmobile[] Returns an array of Carouselmobile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Carouselmobile
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
