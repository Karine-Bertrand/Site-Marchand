<?php

namespace App\Entity;

use App\Repository\DriveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriveRepository::class)
 */
class Drive
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Company::class, mappedBy="drive")
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity=Ordered::class, mappedBy="drive")
     */
    private $ordereds;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="drive", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->ordereds = new ArrayCollection();
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

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->addDrive($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
            $company->removeDrive($this);
        }

        return $this;
    }

    /**
     * @return Collection|Ordered[]
     */
    public function getOrdereds(): Collection
    {
        return $this->ordereds;
    }

    public function addOrdered(Ordered $ordered): self
    {
        if (!$this->ordereds->contains($ordered)) {
            $this->ordereds[] = $ordered;
            $ordered->setDrive($this);
        }

        return $this;
    }

    public function removeOrdered(Ordered $ordered): self
    {
        if ($this->ordereds->contains($ordered)) {
            $this->ordereds->removeElement($ordered);
            // set the owning side to null (unless already changed)
            if ($ordered->getDrive() === $this) {
                $ordered->setDrive(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }
}
