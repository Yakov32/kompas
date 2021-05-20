<?php


namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use const http\Client\Curl\PROXY_HTTP;


class ProductController extends AbstractController
{
    /**
     *@Route ("/products")
     */
    public function products()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render(
            'product/all_products.html.twig',
            [
                'products' => $products,
                'categories' => $categories
            ]);
    }


    /**
     * @Route("/product_add")
     */
    public function addProduct(Request $request, ValidatorInterface $validator)
    {
        //dd($request->request->all(), $_FILES);
        $productDTO = new ProductDTO();

        $productDTO->name = $request->request->get('product_name');
        $productDTO->price = (int)$request->request->get('product_price');
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => $request->request->get('product_category')]);
        $productDTO->category = $category;
        $productDTO->created_at = new \DateTime();

        $res = false;
        if (!empty($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $img = $_FILES['product_image'];
            $newName = bin2hex(random_bytes(15)). $img['name'];
            $res = move_uploaded_file($img['tmp_name'], $_SERVER['DOCUMENT_ROOT']. '/assets/images/'.$newName);
            if ($res) {
                $productDTO->imgPath = $newName;
            }
        }

        $errors = $validator->validate($productDTO);

        if (count($errors) > 0) {
            $errorsMessages = [];
            foreach ($errors as $violation) {
                $errorsMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            dd($errorsMessages);
        }

        $product = new Product();
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setCategory($productDTO->category);
        $product->setCreatedAt($productDTO->created_at);
        $product->setImgPath($productDTO->imgPath);

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return new Response('success!');
    }

    /**
     * @Route ("/category/{category}")
     */
    public function getProductsByCategory($category)
    {
        $em = $this->getDoctrine()->getManager();

        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findProductsByCategoryRoute($category);
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('product/all_products.html.twig',
            [
                'products' => $products,
                'categories' => $categories]);
    }
}