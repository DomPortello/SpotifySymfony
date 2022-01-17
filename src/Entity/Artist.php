<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
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

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nbAlbum;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Album::class, orphanRemoval: true)]
    private Collection $albums;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Track::class, orphanRemoval: true)]
    private Collection $tracks;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->tracks = new ArrayCollection();
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

    public function getNbAlbum(): ?int
    {
        return $this->nbAlbum;
    }

    public function setNbAlbum(?int $nbAlbum): self
    {
        $this->nbAlbum = $nbAlbum;

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
            $album->setArtist($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getArtist() === $this) {
                $album->setArtist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setArtist($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getArtist() === $this) {
                $track->setArtist(null);
            }
        }

        return $this;
    }
}
