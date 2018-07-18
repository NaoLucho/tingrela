<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EcommerceConfigRepository")
 */
class EcommerceConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $tva;

    public function getId()
    {
        return $this->id;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }
}
