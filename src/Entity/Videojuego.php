<?php

namespace App\Entity;

use App\Repository\VideojuegoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideojuegoRepository::class)
 */
class Videojuego implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="El campo nombre no puede estar vacío")
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @Assert\NotBlank(message="El campo lanzamiento no puede estar vacío")
     * @ORM\Column(type="date")
     */
    private $lanzamiento;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaHoraEntrada;

    /**
     * @Assert\NotBlank(message="El campo precio no puede estar vacío")
     * @Assert\Positive(message="El campo precio debe de ser mayor a 0")
     * @Assert\Type(type="float",message="El campo precio debe de ser numérico o decimal")
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @Assert\NotBlank(message="El campo descuento no puede estar vacío")
     * @Assert\PositiveOrZero(message="El campo descuento debe ser mayor o igual a 0")
     * @Assert\Type(type="float", message="Campo Precio numerico o decimal")
     * @ORM\Column(type="float", nullable=true)
     */
    private $descuento;

    /**
     * @Assert\NotBlank(message="El campo stock no puede estar vacío")
     * @Assert\PositiveOrZero(message="El campo stock debe ser mayor o igual a 0")
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @Assert\NotBlank(message="El campo descripción no puede estar vacío")
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @Assert\NotBlank(message="Campo plataforma no seleccionado")
     * @ORM\ManyToOne(targetEntity=Plataforma::class, inversedBy="videojuegos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plataforma;

    /**
     * @Assert\NotBlank(message="Campo categoría no seleccionado")
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="videojuegos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="text")
     */
    private $imgPrincipal;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $imagenes = [];

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

    public function getImgPrincipal(): ?string
    {
        return $this->imgPrincipal;
    }

    public function setImgPrincipal(string $imgPrincipal): self
    {
        $this->imgPrincipal = $imgPrincipal;

        return $this;
    }

    public function getImagenes(): ?array
    {
        return $this->imagenes;
    }

    public function setImagenes(?array $imagenes): self
    {
        $this->imagenes = $imagenes;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'=>$this->getId(),
            'nombre'=>$this->getNombre(),
            'lanzamiento'=>$this->getLanzamiento(),
            'fechaHoraEntrada'=>$this->getFechaHoraEntrada(),
            'precio'=>$this->getPrecio(),
            'descuento'=>$this->getDescuento(),
            'stock'=>$this->getStock(),
            'descripcion'=>$this->getDescripcion(),
            'plataforma'=>$this->getPlataforma()->getNombre(),
            'categoria'=>$this->getCategoria()->getNombre(),
            'imgPrincipal'=>$this->getImgPrincipal(),
            'imagenes'=>$this->getImagenes(),
        ];
    }
}
