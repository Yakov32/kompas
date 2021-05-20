<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Name of product must be at least 2"
     * )
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "Price can't be at least 10 rub"
     * )
     * @Assert\Type("int")
     */
    public $price;

    /**
     * @Assert\NotBlank
     * @Assert\Type("App\Entity\Category")
     */
    public $category;

    /**
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     */
    public $created_at;

    /**
     * @Assert\Type("string")
     */
    public $imgPath;
}