<?php


namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class FilterDTO
{
    /**
     * @Assert\Type("bool")
     */
    public $inStock;

    /**
     * @Assert\Type("bool")
     */
    public $inOrder;

    /**
     * @Assert\Type("int")
     * @Assert\Length(
     *     min = 1,
     *     minMessage = "Price can't be smaller then 1"
     * )
     */
    public $minPrice;

    /**
     * @Assert\Type("int")
     */
    public $maxPrice;
}