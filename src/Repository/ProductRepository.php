<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function findProductsByCategoryRoute(string $categoryRoute)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category' , 'cat')
            ->where("cat.route = :route")
            ->setParameter('route', $categoryRoute)
            ->getQuery()
            ->execute();
    }

    public function findByQueryName($name)
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :name')
            ->setParameter('name', '%'.$name. '%')
            ->getQuery()
            ->execute();
    }
}
