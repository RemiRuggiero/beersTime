<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// pour ajouter des instructions dans les commentaires pour les erreurs form
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un nom pour votre événement" )
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     * minMessage = "Le nom doit comporter au minimum {{ limit }} caractères",
     * maxMessage = "Le nom doit comporter au maximum {{ limit }} caractères",
     * )
     * 
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une description pour votre événement" )
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "La description doit comporter au minimum {{ limit }} caractères",
     *      maxMessage = "La description doit comporter au maximum {{ limit }} caractères",
     * )
     * @ORM\Column(type="string", length=255) // changer un text et faire une migration sur ma BDD
     */
    private $description;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une URL d'image pour votre événement" )
     * @Assert\Url(
     *    message = "Vous devez saisir une URL valide",
     * )
     * @Assert\Regex("/^.*\.(jpg|jpeg|png|webp)$/", message = "Votre URL doit pointer sur une image")
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une date de début pour votre événement" )
     * @Assert\GreaterThan("now", message = "Vous devez choisir une date future" )*
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une date de fin pour votre événement" )
     * @Assert\GreaterThan(propertyPath = "startAt", message = "Vous devez choisir une date supérieure à la date de début" )
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @Assert\Positive( message = "Vous devez saisir un montant supérieur à 0" )
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @Assert\Positive( message = "Vous devez saisir une capacité supérieur à 0" )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="event", orphanRemoval=true)
     */
    private $participations;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->participations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt = null): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt = null): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function autoSetCreatedAt(){
        $this->createdAt = new \DateTime();
    }
}
