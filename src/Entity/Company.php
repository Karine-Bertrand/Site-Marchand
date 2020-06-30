<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $siret;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="company", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Drive::class, inversedBy="companies")
     */
    private $drive;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="company", orphanRemoval=true)
     */
    private $review;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="company", orphanRemoval=true)
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=Ordered::class, mappedBy="company", orphanRemoval=true)
     */
    private $ordered;

    public function __construct()
    {
        $this->drive = new ArrayCollection();
        $this->review = new ArrayCollection();
        $this->stock = new ArrayCollection();
        $this->ordered = new ArrayCollection();
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newCompany = null === $user ? null : $this;
        if ($user->getCompany() !== $newCompany) {
            $user->setCompany($newCompany);
        }

        return $this;
    }

    /**
     * @return Collection|Drive[]
     */
    public function getDrive(): Collection
    {
        return $this->drive;
    }

    public function addDrive(Drive $drive): self
    {
        if (!$this->drive->contains($drive)) {
            $this->drive[] = $drive;
        }

        return $this;
    }

    public function removeDrive(Drive $drive): self
    {
        if ($this->drive->contains($drive)) {
            $this->drive->removeElement($drive);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReview(): Collection
    {
        return $this->review;
    }

    public function addReview(Review $review): self
    {
        if (!$this->review->contains($review)) {
            $this->review[] = $review;
            $review->setCompany($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->review->contains($review)) {
            $this->review->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getCompany() === $this) {
                $review->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock[] = $stock;
            $stock->setCompany($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->contains($stock)) {
            $this->stock->removeElement($stock);
            // set the owning side to null (unless already changed)
            if ($stock->getCompany() === $this) {
                $stock->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ordered[]
     */
    public function getOrdered(): Collection
    {
        return $this->ordered;
    }

    public function addOrdered(Ordered $ordered): self
    {
        if (!$this->ordered->contains($ordered)) {
            $this->ordered[] = $ordered;
            $ordered->setCompany($this);
        }

        return $this;
    }

    public function removeOrdered(Ordered $ordered): self
    {
        if ($this->ordered->contains($ordered)) {
            $this->ordered->removeElement($ordered);
            // set the owning side to null (unless already changed)
            if ($ordered->getCompany() === $this) {
                $ordered->setCompany(null);
            }
        }

        return $this;
    }


    public function getAverageRating(){

        $avis = $this->getReview();

        $totalAvis = 0;
        $nbAvis = 0;

        foreach ($avis as $avi) {
            $totalAvis += $avi->getRating();
            $nbAvis++;
        }

        if ($nbAvis > 0) { return $totalAvis/$nbAvis; }

        return null;
    }
}
