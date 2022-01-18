<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function getAllAlbumsWithRelations(string $order = 'album.releaseAt'): array
    {
        return $this->createQueryBuilder('album')
            ->select('album','artist','genre', 'tracks')
            ->leftJoin('album.artist', 'artist')
            ->leftJoin('album.genre', 'genre')
            ->leftJoin('album.tracks', 'tracks')
            ->orderBy($order, 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getQbAll(): QueryBuilder
    {
        return $this->createQueryBuilder('album')
            ->select('album','artist','genre', 'tracks')
            ->leftJoin('album.artist', 'artist')
            ->leftJoin('album.genre', 'genre')
            ->leftJoin('album.tracks', 'tracks')
            ->orderBy('album.releaseAt', 'DESC');
//            ->getQuery()
//            ->getResult();
    }

    // /**
    //  * @return Album[] Returns an array of Album objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Album
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByAlpha($orderBy = 'a.title', $order = 'ASC')
    {
        return $this->createQueryBuilder('a')
            ->orderBy($orderBy, $order);
    }
}
