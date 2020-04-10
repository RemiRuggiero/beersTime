<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $bookingNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bookingAt;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getBookingNumber(): ?string
    {
        return $this->bookingNumber;
    }

    public function setBookingNumber(string $bookingNumber): self
    {
        $this->bookingNumber = $bookingNumber;

        return $this;
    }

    public function getBookingAt(): ?\DateTimeInterface
    {
        return $this->bookingAt;
    }

    public function setBookingAt(\DateTimeInterface $bookingAt): self
    {
        $this->bookingAt = $bookingAt;

        return $this;
    }
}
