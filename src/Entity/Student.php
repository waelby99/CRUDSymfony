<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Classroom;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $NSC = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $id_classroom = null;

    #[ORM\ManyToMany(targetEntity: Club::class, inversedBy: 'students')]
    #[ORM\JoinTable(name:'student_club')]
    #[ORM\JoinColumn(name:'student_id',referencedColumnName:'id')]
    #[ORM\InverseJoinColumn(name:'club_id',referencedColumnName:'id')]
    private Collection $clubs;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNSC(): ?string
    {
        return $this->NSC;
    }

    public function setNSC(string $NSC): self
    {
        $this->NSC = $NSC;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdClassroom(): ?Classroom
    {
        return $this->id_classroom;
    }

    public function setIdClassroom(?Classroom $id_classroom): self
    {
        $this->id_classroom = $id_classroom;

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        $this->clubs->removeElement($club);

        return $this;
    }

}
