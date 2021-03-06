<?php

namespace App\Entity;

use App\Repository\PlataformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"nombre"},message="El nombre de esta plataforma ya existe en BD")
 * @ORM\Entity(repositoryClass=PlataformaRepository::class)
 */
class Plataforma implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\NotBlank(message="El campo nombre no puede estar vacío")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Videojuego::class, mappedBy="plataforma")
     */
    private $videojuegos;

    public function __construct()
    {
        $this->videojuegos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Videojuego[]
     */
    public function getVideojuegos(): Collection
    {
        return $this->videojuegos;
    }

    public function addVideojuego(Videojuego $videojuego): self
    {
        if (!$this->videojuegos->contains($videojuego)) {
            $this->videojuegos[] = $videojuego;
            $videojuego->setPlataforma($this);
        }

        return $this;
    }

    public function removeVideojuego(Videojuego $videojuego): self
    {
        if ($this->videojuegos->removeElement($videojuego)) {
            // set the owning side to null (unless already changed)
            if ($videojuego->getPlataforma() === $this) {
                $videojuego->setPlataforma(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=>$this->getId(),
            'nombre'=>$this->getNombre(),
        ];
    }
    public function __toString(): ?string
    {
        return $this->getNombre();
    }

}
