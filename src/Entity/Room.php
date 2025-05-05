<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cinema $cinema = null;

    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $seats;

    #[ORM\OneToMany(targetEntity: Showtime::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $showtimes;

    #[ORM\OneToMany(targetEntity: Incident::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $incidents;

    #[ORM\Column(nullable: true)]
    private ?array $projectionEquipment = null;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
        $this->showtimes = new ArrayCollection();
        $this->incidents = new ArrayCollection();
    }

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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;
        return $this;
    }

    /** @return Collection<int, Seat> */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setRoom($this);
        }
        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            if ($seat->getRoom() === $this) {
                $seat->setRoom(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Showtime> */
    public function getShowtimes(): Collection
    {
        return $this->showtimes;
    }

    public function addShowtime(Showtime $showtime): self
    {
        if (!$this->showtimes->contains($showtime)) {
            $this->showtimes[] = $showtime;
            $showtime->setRoom($this);
        }
        return $this;
    }

    public function removeShowtime(Showtime $showtime): self
    {
        if ($this->showtimes->removeElement($showtime)) {
            if ($showtime->getRoom() === $this) {
                $showtime->setRoom(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Incident> */
    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incident $incident): self
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents[] = $incident;
            $incident->setRoom($this);
        }
        return $this;
    }

    public function removeIncident(Incident $incident): self
    {
        if ($this->incidents->removeElement($incident)) {
            if ($incident->getRoom() === $this) {
                $incident->setRoom(null);
            }
        }
        return $this;
    }

    public function getProjectionEquipment(): ?array
    {
        return $this->projectionEquipment;
    }

    public function setProjectionEquipment(?array $projectionEquipment): static
    {
        $this->projectionEquipment = $projectionEquipment;

        return $this;
    }
}
