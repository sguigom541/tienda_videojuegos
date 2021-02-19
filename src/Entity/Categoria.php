<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="El campo nombre no puede estar vacío")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Videojuego::class, mappedBy="categoria")
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

    public function setNombre(string $nombre): self
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
            $videojuego->setCategoria($this);
        }

        return $this;
    }

    public function removeVideojuego(Videojuego $videojuego): self
    {
        if ($this->videojuegos->removeElement($videojuego)) {
            // set the owning side to null (unless already changed)
            if ($videojuego->getCategoria() === $this) {
                $videojuego->setCategoria(null);
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
}
