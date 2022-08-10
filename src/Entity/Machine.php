<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $ramQuantity = null;

    #[ORM\Column(length: 20)]
    private ?string $ramType = null;

    #[ORM\Column(length: 20)]
    private ?string $hardDiskType = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hardDiskQuantity = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hardDiskSize = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hardDiskTotalCapacityTb = null;

    #[ORM\ManyToOne(inversedBy: 'machines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 50)]
    private ?string $currency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRamQuantity(): ?int
    {
        return $this->ramQuantity;
    }

    public function setRamQuantity(int $ramQuantity): self
    {
        $this->ramQuantity = $ramQuantity;

        return $this;
    }

    public function getRamType(): ?string
    {
        return $this->ramType;
    }

    public function setRamType(string $ramType): self
    {
        $this->ramType = $ramType;

        return $this;
    }

    public function getHardDiskType(): ?string
    {
        return $this->hardDiskType;
    }

    public function setHardDiskType(string $hardDiskType): self
    {
        $this->hardDiskType = $hardDiskType;

        return $this;
    }

    public function getHardDiskQuantity(): ?int
    {
        return $this->hardDiskQuantity;
    }

    public function setHardDiskQuantity(int $hardDiskQuantity): self
    {
        $this->hardDiskQuantity = $hardDiskQuantity;

        return $this;
    }

    public function getHardDiskSize(): ?int
    {
        return $this->hardDiskSize;
    }

    public function setHardDiskSize(int $hardDiskSize): self
    {
        $this->hardDiskSize = $hardDiskSize;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHardDiskTotalCapacityTb(): ?int
    {
        return $this->hardDiskTotalCapacityTb;
    }

    /**
     * @param int|null $hardDiskTotalCapacityTb
     */
    public function setHardDiskTotalCapacityTb(?int $hardDiskTotalCapacityTb): void
    {
        $this->hardDiskTotalCapacityTb = $hardDiskTotalCapacityTb;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
