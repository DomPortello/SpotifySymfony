<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $smallPicture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $mediumPicture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $bigPicture;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $xlPicture;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'genre')]
    private Collection $albums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
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

    public function getSmallPicture(): ?string
    {
        return $this->smallPicture;
    }

    public function setSmallPicture(?string $smallPicture): self
    {
        $this->smallPicture = $smallPicture;

        return $this;
    }

    public function getMediumPicture(): ?string
    {
        return $this->mediumPicture;
    }

    public function setMediumPicture(?string $mediumPicture): self
    {
        $this->mediumPicture = $mediumPicture;

        return $this;
    }

    public function getBigPicture(): ?string
    {
        return $this->bigPicture;
    }

    public function setBigPicture(?string $bigPicture): self
    {
        $this->bigPicture = $bigPicture;

        return $this;
    }

    public function getXlPicture(): ?string
    {
        return $this->xlPicture;
    }

    public function setXlPicture(?string $xlPicture): self
    {
        $this->xlPicture = $xlPicture;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addGenre($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->removeElement($album)) {
            $album->removeGenre($this);
        }

        return $this;
    }
}
