<?php

namespace App\Entity;

use App\Entity\Song;
use App\Repository\SingerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SingerRepository::class)]
class Singer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, song>
     */
    #[ORM\ManyToMany(targetEntity: song::class, inversedBy: 'singers')]
    private Collection $songFK;

    public function __construct()
    {
        $this->songFK = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, song>
     */
    public function getSongFK(): Collection
    {
        return $this->songFK;
    }

    public function addSongFK(song $songFK): static
    {
        if (!$this->songFK->contains($songFK)) {
            $this->songFK->add($songFK);
        }

        return $this;
    }

    public function removeSongFK(song $songFK): static
    {
        $this->songFK->removeElement($songFK);

        return $this;
    }
}
