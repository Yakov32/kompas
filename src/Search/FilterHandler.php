<?php


namespace App\Search;


use App\DTO\FilterDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilterHandler
{
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function handleFilters(Request $request)
    {
        if (empty($request->query->get('filter'))) {
            return false;
        }

        $filterDTO = $this->createFilter($request);

        $errors = $this->validateFilter($filterDTO);

        //dd($filterDTO);

        if ($errors !== Null) {
            return $errors;
        }

        return $filterDTO;
    }

    private function createFilter(Request $request)
    {
        $filter = $request->query->get('filter');
        $filterDTO = new FilterDTO();
        $filterDTO->inStock = isset($filter['in_stock']) ? boolval($filter['in_stock']) : false;
        $filterDTO->inOrder = isset($filter['in_order']) ? boolval($filter['in_order']) : false;
        $filterDTO->minPrice = isset($filter['price_min']) ? (int)$filter['price_min'] : 1;
        $filterDTO->maxPrice = isset($filter['price_max']) ? (int)$filter['price_max'] : 99999;

        return $filterDTO;
    }
    private function validateFilter(FilterDTO $filterDTO)
    {
        $errors = $this->validator->validate($filterDTO);

        if (count($errors) > 0) {
            $errorsMessages = [];
            foreach ($errors as $violation) {
                $errorsMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $errorsMessages;
        }
        return Null;
    }
}