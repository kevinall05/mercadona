<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PromotionsRepository::class)]
class Promotions
{
    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Products::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $products;

    private $dateDeb;
    private $dateFin;
    private $pourcentage;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?string
    {
        return $this->dateDeb;
    }

    public function setDateDeb(string $dateDeb): self
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function setDateFin(string $dateFin): self
    {
        $this->dateFin = $dateFin;
        
        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }
}
