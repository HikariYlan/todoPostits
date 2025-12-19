<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, PostIt>
     */
    #[ORM\ManyToMany(targetEntity: PostIt::class, mappedBy: 'tags')]
    private Collection $postIts;

    public function __construct()
    {
        $this->postIts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, PostIt>
     */
    public function getPostIts(): Collection
    {
        return $this->postIts;
    }

    public function addPostIt(PostIt $postIt): static
    {
        if (!$this->postIts->contains($postIt)) {
            $this->postIts->add($postIt);
            $postIt->addTag($this);
        }

        return $this;
    }

    public function removePostIt(PostIt $postIt): static
    {
        if ($this->postIts->removeElement($postIt)) {
            $postIt->removeTag($this);
        }

        return $this;
    }
}
