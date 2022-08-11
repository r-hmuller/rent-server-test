<?php

namespace App\Repository;

use App\Entity\Machine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Machine>
 *
 * @method Machine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Machine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Machine[]    findAll()
 * @method Machine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Machine::class);
    }

    public function add(Machine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Machine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('machines');

        foreach($filters as $filter => $value) {
            if ($filter === 'hardDiskType') {
                $qb->andWhere("machines.hardDiskType = :hddType");
                $qb->setParameter('hddType', $value);
            }
            else if ($filter === 'location') {
                $qb->innerJoin('machines.location', 'location', 'WITH', 'location.name = :location');
                $qb->setParameter('location', $value);
            }
            else if ($filter === 'hardDiskCapacity') {
                $qb->andWhere('machines.hardDiskTotalCapacityGb >= :hddCapacity');
                $qb->setParameter('hddCapacity', $value);
            } else if ($filter === 'ram') {
                $qb->andWhere('machines.ramQuantity IN (:ram)');
                $qb->setParameter('ram', $value);
            }
        }

        $query = $qb->getQuery();
        return $query->execute();
    }
}
