<?php

namespace App\Repository;

use App\Entity\EcommerceConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EcommerceConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method EcommerceConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method EcommerceConfig[]    findAll()
 * @method EcommerceConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcommerceConfigRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EcommerceConfig::class);
    }

//    /**
//     * @return EcommerceConfig[] Returns an array of EcommerceConfig objects
//     */
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
    public function findOneBySomeField($value): ?EcommerceConfig
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
