<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'album', cascade: ['remove'], orphanRemoval: true)]
    private Collection $medias;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): void
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setAlbum($this);
        }
    }

    public function removeMedia(Media $media): void
    {
        if ($this->medias->removeElement($media)) {
            if ($media->getAlbum() === $this) {
                $media->setAlbum(null);
            }
        }
    }
}
