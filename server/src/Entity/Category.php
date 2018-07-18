<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Product;
use App\Entity\Type;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
    * @ORM\ManyToMany(targetEntity="App\Entity\Product", cascade={"persist"}, inversedBy="categories")
    * @ORM\JoinTable(name="category_product")
    */
    private $products;
    
    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="categories")
     * @var Type
     */
    protected $type;

    public function __toString() {
        return $this->category;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

        /**
     * Products
     * @param Product $product [description]
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

        /**
     * Type
     * @param Type $type [description]
     */
    public function setType(Type $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
}
