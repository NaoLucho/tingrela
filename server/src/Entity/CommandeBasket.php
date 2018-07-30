<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Product;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeBasketRepository")
 */
class CommandeBasket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="commandeBaskets")
     */
    private $commande;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function __toString() {
        return (string)$this->product . ': ' . $this->quantity . ' ' . $this->getProduct()->getUnit();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commande
     *
     * @param Commande $commande
     *
     * @return CommandeBasket
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return CommandeBasket
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
