<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getActiveOrderByUserAndStatus(int $id, string $status = 'active'): ?Order
    {
        return $this->createQueryBuilder('cart')
            ->select('cart')
            ->leftJoin('cart.user', 'user')
            ->where('user.id = :id')
            ->andWhere('cart.status = :status')
            ->setParameter(':id', $id)
            ->setParameter(':status', $status)

            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findActiveOrderByUserWithProducts(User $user): ?Order
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'orderLines', 'album', 'track', 'artist' )
            ->leftJoin('o.orderLines', 'orderLines')
            ->leftJoin('orderLines.album', 'album')
            ->leftJoin('album.artist', 'artist')
            ->leftJoin('orderLines.track', 'track')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->andWhere('o.status = :active')
            ->setParameter('active', 'active')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOrderByIdWithProducts(int $id): ?Order
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'orderLines', 'album', 'track', 'artist' )
            ->leftJoin('o.orderLines', 'orderLines')
            ->leftJoin('orderLines.album', 'album')
            ->leftJoin('album.artist', 'artist')
            ->leftJoin('orderLines.track', 'track')
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByAlpha($orderBy = 'o.endedAt', $order = 'DESC')
    {
        return $this->createQueryBuilder('o')
            ->orderBy($orderBy, $order);
    }
}
