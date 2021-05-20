<?php

namespace App\Search;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ProductSearcher
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function searchByName($query)
    {
        $productRepository = $this->em->getRepository(Product::class);

        $products = $productRepository->findByQueryName($query);

        return $products;
    }
}