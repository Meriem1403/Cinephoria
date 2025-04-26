<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $rowLabel = null;

    #[ORM\Column]
    private ?int $seatNumber = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPMR = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isReserved = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBroken = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRowLabel(): ?string
    {
        return $this->rowLabel;
    }

    public function setRowLabel(string $rowLabel): static
    {
        $this->rowLabel = $rowLabel;

        return $this;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seatNumber;
    }

    public function setSeatNumber(int $seatNumber): static
    {
        $this->seatNumber = $seatNumber;

        return $this;
    }

    public function isPMR(): ?bool
    {
        return $this->isPMR;
    }

    public function setIsPMR(?bool $isPMR): static
    {
        $this->isPMR = $isPMR;

        return $this;
    }

    public function isReserved(): ?bool
    {
        return $this->isReserved;
    }

    public function setIsReserved(?bool $isReserved): static
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    public function isBroken(): ?bool
    {
        return $this->isBroken;
    }

    public function setIsBroken(?bool $isBroken): static
    {
        $this->isBroken = $isBroken;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }
}
