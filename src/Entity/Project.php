<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_project"})
     */

    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ProjectTime::class, mappedBy="projectName", orphanRemoval=true, cascade={"persist"})
     */
    private $projectReports;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function __construct()
    {
        $this->projectReports = new ArrayCollection();
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
     * @return Collection|ProjectTime[]
     */
    public function getProjectReports(): Collection
    {
        return $this->projectReports;
    }

    public function addProjectReport(ProjectTime $projectReport): self
    {
        if (!$this->projectReports->contains($projectReport)) {
            $this->projectReports[] = $projectReport;
            $projectReport->setProjectName($this);
        }

        return $this;
    }

    public function removeProjectReport(ProjectTime $projectReport): self
    {
        if ($this->projectReports->removeElement($projectReport)) {
            // set the owning side to null (unless already changed)
            if ($projectReport->getProjectName() === $this) {
                $projectReport->setProjectName(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
