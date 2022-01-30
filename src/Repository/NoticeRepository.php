<?php

namespace App\Repository;

use App\Entity\Notice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notice[]    findAll()
 * @method Notice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notice::class);
    }

    // /**
    //  * @return Returns a Notice by email and city
    //  */
    public function findOneByEmailCity($email, $city): ?Notice
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.email = :email')
            ->setParameter('email', $email)
            ->andWhere('n.city = :city')
            ->setParameter('city', $city)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Returns an array of every city in the database, once
    //  */
    public function findEveryCity(): array
    {
        return $this->createQueryBuilder('n')
            ->select('n.city')
            ->groupBy('n.city')
            ->getQuery()
            ->getResult()
        ;
    }
}
