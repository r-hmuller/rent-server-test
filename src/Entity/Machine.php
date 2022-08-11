<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("machine")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("machine")]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups("machine")]
    private ?int $ramQuantity = null;

    #[ORM\Column(length: 20)]
    #[Groups("machine")]
    private ?string $ramType = null;

    #[ORM\Column(length: 20)]
    #[Groups("machine")]
    private ?string $hardDiskType = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups("machine")]
    private ?int $hardDiskQuantity = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups("machine")]
    private ?int $hardDiskSize = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups("machine")]
    private ?int $hardDiskTotalCapacityGb = null;

    #[ORM\ManyToOne(inversedBy: 'machines')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("machine")]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups("machine")]
    private ?string $price = null;

    #[ORM\Column(length: 50)]
    #[Groups("machine")]
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
    public function getHardDiskTotalCapacityGb(): ?int
    {
        return $this->hardDiskTotalCapacityGb;
    }

    /**
     * @param int|null $hardDiskTotalCapacityGb
     */
    public function setHardDiskTotalCapacityGb(?int $hardDiskTotalCapacityGb): void
    {
        $this->hardDiskTotalCapacityGb = $hardDiskTotalCapacityGb;
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
