<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReservationRepository;
use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Showtime $showtime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $reservationDate = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\OneToMany(targetEntity: ReservationSeats::class, mappedBy: 'reservation', cascade: ['persist'], orphanRemoval: true)]
    private Collection $reservationSeats;

    public function __construct()
    {
        $this->reservationSeats = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setReservationDateValue(): void
    {
        if ($this->reservationDate === null) {
            $this->reservationDate = new DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getShowtime(): ?Showtime
    {
        return $this->showtime;
    }

    public function setShowtime(?Showtime $showtime): self
    {
        $this->showtime = $showtime;
        return $this;
    }

    public function getReservationDate(): ?DateTimeInterface
    {
        return $this->reservationDate;
    }

    public function setReservationDate(DateTimeInterface $reservationDate): self
    {
        $this->reservationDate = $reservationDate;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @return Collection<int, ReservationSeats>
     */
    public function getReservationSeats(): Collection
    {
        return $this->reservationSeats;
    }

    public function addReservationSeat(ReservationSeats $reservationSeat): self
    {
        if (!$this->reservationSeats->contains($reservationSeat)) {
            $this->reservationSeats[] = $reservationSeat;
            $reservationSeat->setReservation($this);
        }
        return $this;
    }

    public function removeReservationSeat(ReservationSeats $reservationSeat): self
    {
        if ($this->reservationSeats->removeElement($reservationSeat)) {
            if ($reservationSeat->getReservation() === $this) {
                $reservationSeat->setReservation(null);
            }
        }
        return $this;
    }
}
