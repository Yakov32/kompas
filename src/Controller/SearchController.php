<?php


namespace App\Controller;

use App\Entity\Category;
use App\Search\ProductSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private ProductSearcher $productSearcher;

    public function __construct(ProductSearcher $productSearcher)
    {
        $this->productSearcher = $productSearcher;
    }

    /**
     * @Route ("/search", name="search")
     */
    public function searchByQuery(Request $request)
    {
        $query = $request->query->get('q');

        $products = $this->productSearcher->searchByName($query);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('search/searched-products.html.twig',
            [
                'products' => $products,
                'categories' => $categories]);
    }

    /**
     * @Route ("/search-filter", name="search_filter")
     */
    public function searchByQueryFilter(Request $request)
    {
        $products = $this->productSearcher->searchWithFilters($request);

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('search/filter-searched-products.html.twig',
            [
                'categories' => $categories,
                'products'   => $products
            ]);
    }
}