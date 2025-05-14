<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'seats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\OneToMany(targetEntity: ReservationSeats::class, mappedBy: 'seat', orphanRemoval: true)]
    private Collection $reservationSeats;

    public function __construct()
    {
        $this->reservationSeats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRowLabel(): ?string
    {
        return $this->rowLabel;
    }

    public function setRowLabel(string $rowLabel): self
    {
        $this->rowLabel = $rowLabel;
        return $this;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seatNumber;
    }

    public function setSeatNumber(int $seatNumber): self
    {
        $this->seatNumber = $seatNumber;
        return $this;
    }

    public function getIsPMR(): ?bool
    {
        return $this->isPMR;
    }

    public function setIsPMR(?bool $isPMR): self
    {
        $this->isPMR = $isPMR;
        return $this;
    }

    public function getIsReserved(): ?bool
    {
        return $this->isReserved;
    }

    public function setIsReserved(?bool $isReserved): self
    {
        $this->isReserved = $isReserved;
        return $this;
    }

    public function getIsBroken(): ?bool
    {
        return $this->isBroken;
    }

    public function setIsBroken(?bool $isBroken): self
    {
        $this->isBroken = $isBroken;
        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;
        return $this;
    }

    /** @return Collection<int, ReservationSeats> */
    public function getReservationSeats(): Collection
    {
        return $this->reservationSeats;
    }

    public function addReservationSeat(ReservationSeats $reservationSeat): self
    {
        if (!$this->reservationSeats->contains($reservationSeat)) {
            $this->reservationSeats[] = $reservationSeat;
            $reservationSeat->setSeat($this);
        }
        return $this;
    }

    public function removeReservationSeat(ReservationSeats $reservationSeat): self
    {
        if ($this->reservationSeats->removeElement($reservationSeat)) {
            if ($reservationSeat->getSeat() === $this) {
                $reservationSeat->setSeat(null);
            }
        }
        return $this;
    }
    public function getLabel(): string
    {
        return strtoupper($this->rowLabel . $this->seatNumber);
    }
    public function getStatus(): string
    {
        if ($this->isBroken) return 'defective';
        if ($this->isReserved) return 'reserved';
        if ($this->isPMR) return 'pmr';
        return 'standard';
    }
    public function isCurrentlyReserved(): bool
    {
        return !$this->reservationSeats->isEmpty();
    }


}
