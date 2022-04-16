<?php

namespace App\Repository;

use App\Entity\Alerts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alerts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alerts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alerts[]    findAll()
 * @method Alerts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alerts::class);
    }

    public function getAlertsDay()
    {
        $builder = $this->createQueryBuilder('a');

        return $builder
            ->orderBy('a.started_at', 'ASC')
            ->andWhere('a.started_at > :time')
            ->setParameter('time', strtotime((new \DateTime())->format('Y-m-d')))
            ->getQuery()
            ->getResult();
    }

    public function active(){
        $builder = $this->createQueryBuilder('a');

        return $builder
            ->andWhere('a.status = :status')
            ->setParameter('status', false)
            ->getQuery()
            ->getResult();
    }
}
