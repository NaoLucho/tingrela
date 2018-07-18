<?php

namespace App\Repository;

use App\Entity\CommandeBasket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandeBasket|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeBasket|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeBasket[]    findAll()
 * @method CommandeBasket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeBasketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandeBasket::class);
    }

//    /**
//     * @return CommandeBasket[] Returns an array of CommandeBasket objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandeBasket
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
