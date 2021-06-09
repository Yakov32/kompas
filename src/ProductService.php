<?php


namespace App;


use App\DTO\ProductDTO;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    public function createProduct(Request $request)
    {
        $productDTO = $this->createProductDTO($request);

        $errors = $this->validateProductDTO($productDTO);

        if ($errors) {
            dd($errors);
        }

        $product = new Product();

        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setCategory($productDTO->category);
        $product->setCreatedAt($productDTO->created_at);

        if ($productDTO->imgPath) {
            $product->setImgPath($productDTO->imgPath);
        }

        $this->saveProduct($product);

        return 1;
    }

    private function validateProductDTO(ProductDTO $productDTO)
    {
        $errors = $this->validator->validate($productDTO);

        if (count($errors) > 0) {
            $errorsMessages = [];
            foreach ($errors as $violation) {
                $errorsMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $errorsMessages;
        }
        return Null;
    }

    private function createProductDTO(Request $request)
    {
        $productDTO = new ProductDTO();

        $productDTO->name = $request->request->get('product_name');
        $productDTO->price = (int)$request->request->get('product_price');
        $category = $this->em->getRepository(Category::class)->findOneBy(['name' => $request->request->get('product_category')]);
        $productDTO->category = $category;
        $productDTO->created_at = new \DateTime();

        $res = false;

        if ( !empty($_FILES['product_image']) && $_FILES['product_image']['size'] !== 0 && $_FILES['product_image']['error'] == 0) {
            $img = $_FILES['product_image'];
            $newName = bin2hex(random_bytes(15)). $img['name'];
            $res = move_uploaded_file($img['tmp_name'], $_SERVER['DOCUMENT_ROOT']. '/assets/images/'.$newName);
            if ($res) {
                $productDTO->imgPath = $newName;
            }
        }

        return $productDTO;
    }

    private function saveProduct(Product $product)
    {
        $this->em->persist($product);
        $this->em->flush();
    }
}