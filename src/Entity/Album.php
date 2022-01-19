<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank, Assert\Type('string'), Assert\Length(max: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $smallCover;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $mediumCover;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $bigCover;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $xlCover;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $label;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank, Assert\Positive, Assert\Type('integer')]
    private int $nbTracks;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive, Assert\Type('integer')]
    private ?int $duration;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\DateTime, Assert\Type('datetime')]
    private ?Datetime $releaseAt;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotBlank, Assert\Type('boolean')]
    private bool $available;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string'), Assert\Length(max: 255)]
    private ?string $lyrics;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'albums')]
    private Collection $genre;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Track::class, orphanRemoval: true)]
    private Collection $tracks;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private Artist $artist;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank, Assert\Positive, Assert\Type('float')]
    private float $price;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSmallCover(): ?string
    {
        return $this->smallCover;
    }

    public function setSmallCover(?string $smallCover): self
    {
        $this->smallCover = $smallCover;

        return $this;
    }

    public function getMediumCover(): ?string
    {
        return $this->mediumCover;
    }

    public function setMediumCover(?string $mediumCover): self
    {
        $this->mediumCover = $mediumCover;

        return $this;
    }

    public function getBigCover(): ?string
    {
        return $this->bigCover;
    }

    public function setBigCover(?string $bigCover): self
    {
        $this->bigCover = $bigCover;

        return $this;
    }

    public function getXlCover(): ?string
    {
        return $this->xlCover;
    }

    public function setXlCover(?string $xlCover): self
    {
        $this->xlCover = $xlCover;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getNbTracks(): ?int
    {
        return $this->nbTracks;
    }

    public function setNbTracks(int $nbTracks): self
    {
        $this->nbTracks = $nbTracks;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleaseAt(): ?\DateTimeInterface
    {
        return $this->releaseAt;
    }

    public function setReleaseAt(?\DateTimeInterface $releaseAt): self
    {
        $this->releaseAt = $releaseAt;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genre->removeElement($genre);

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
            $track->setAlbum($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getAlbum() === $this) {
                $track->setAlbum(null);
            }
        }

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
