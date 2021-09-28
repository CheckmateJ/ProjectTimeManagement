<?php

namespace App\Entity;

use App\Repository\ProjectReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProjectReportRepository::class)
 */
class ProjectTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_project"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"show_project"})
     */
    private $createdAt;


    /**
     * @ORM\Column(type="array")
     * @Groups({"show_project"})
     */
    private $timeOfProject = [];

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectReports", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_project"})
     */
    private $projectName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTimeOfProject(): ?array
    {
        return $this->timeOfProject;
    }

    public function setTimeOfProject(array $timeOfProject): self
    {
        $this->timeOfProject = $timeOfProject;

        return $this;
    }

    public function getProjectName(): ?Project
    {
        return $this->projectName;
    }

    public function setProjectName(?Project $projectName): self
    {
        $this->projectName = $projectName;

        return $this;
    }
}
