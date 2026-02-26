<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    /**
     * @var Collection<int, Singer>
     */
    #[ORM\ManyToMany(targetEntity: Singer::class, mappedBy: 'songFK')]
    private Collection $singers;

    public function __construct()
    {
        $this->singers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Singer>
     */
    public function getSingers(): Collection
    {
        return $this->singers;
    }

    public function addSinger(Singer $singer): static
    {
        if (!$this->singers->contains($singer)) {
            $this->singers->add($singer);
            $singer->addSongFK($this);
        }

        return $this;
    }

    public function removeSinger(Singer $singer): static
    {
        if ($this->singers->removeElement($singer)) {
            $singer->removeSongFK($this);
        }

        return $this;
    }
}
