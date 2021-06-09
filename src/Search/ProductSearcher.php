<?php

namespace App\Search;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductSearcher
{
    private EntityManagerInterface $em;
    private FilterHandler $filterHandler;

    public function __construct(EntityManagerInterface $entityManager, FilterHandler $filterHandler)
    {
        $this->em = $entityManager;
        $this->filterHandler = $filterHandler;
    }

    public function searchByName($query)
    {
        $productRepository = $this->em->getRepository(Product::class);

        $products = $productRepository->findByQueryName($query);

        return $products;
    }

    public function searchWithFilters(Request $request)
    {
        $filter = $this->filterHandler->handleFilters($request);
        //dd($filter);
        $productRepository = $this->em->getRepository(Product::class);

        $products = $productRepository->findByQueryWithFilters($filter);

        return $products;
    }
}