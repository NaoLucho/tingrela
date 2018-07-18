<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Category;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
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
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="type")
    */
    private $categories;

    public function __toString() {
        return $this->type;
    }

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function addCategory(Category $categorie)
    {
        $this->categories[] = $categorie;

        return $this;
    }

    public function removeCategory(Category $categorie)
    {
        $this->categories->removeElement($categorie);
    }

    public function getCategories()
    {
        return $this->categories;
    }
}
