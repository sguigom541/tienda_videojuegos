<?php

namespace App\Entity;

use App\Repository\VideojuegoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideojuegoRepository::class)
 */
class Videojuego
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\Column(type="date")
     */
    private $lanzamiento;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaHoraEntrada;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $descuento;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Plataforma::class, inversedBy="videojuegos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plataforma;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="videojuegos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

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

    public function getLanzamiento(): ?\DateTimeInterface
    {
        return $this->lanzamiento;
    }

    public function setLanzamiento(\DateTimeInterface $lanzamiento): self
    {
        $this->lanzamiento = $lanzamiento;

        return $this;
    }

    public function getFechaHoraEntrada(): ?\DateTimeInterface
    {
        return $this->fechaHoraEntrada;
    }

    public function setFechaHoraEntrada(\DateTimeInterface $fechaHoraEntrada): self
    {
        $this->fechaHoraEntrada = $fechaHoraEntrada;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getDescuento(): ?float
    {
        return $this->descuento;
    }

    public function setDescuento(?float $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPlataforma(): ?Plataforma
    {
        return $this->plataforma;
    }

    public function setPlataforma(?Plataforma $plataforma): self
    {
        $this->plataforma = $plataforma;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
}
