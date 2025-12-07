<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\PostItRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: PostItRepository::class)]
class PostIt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    #[Timestampable(on: 'create')]
    private DateTime $creationDate;

    #[ORM\Column(nullable: true)]
    private ?DateTime $finishDate = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $dueDate = null;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private Status $status;

    #[ORM\ManyToOne(inversedBy: 'postits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTime $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getFinishDate(): ?DateTime
    {
        return $this->finishDate;
    }

    public function setFinishDate(?DateTime $finishDate): static
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTime $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
