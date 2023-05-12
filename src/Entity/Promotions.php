<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PromotionsRepository;

#[ORM\Entity(repositoryClass: PromotionsRepository::class)]
class Promotions
{
    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Products::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $products;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_deb = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(type: 'integer')]
    private $pourcentage;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?DateTime
    {
        return $this->date_deb;
    }

    public function setDateDeb(\DateTimeInterface $date_deb): self
    {
        $this->date_deb = $date_deb;

        return $this;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;
        
        return $this;
    }

    public function getDateFin(): ?DateTime
    {
        return $this->date_fin;
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
