<?php

namespace App\Entity;

use App\Repository\DetallePedidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetallePedidoRepository::class)
 */
class DetallePedido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidadCompra;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="float")
     */
    private $precioVideojuego;

    /**
     * @ORM\ManyToOne(targetEntity=Pedido::class, inversedBy="detallePedidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pedido;

    /**
     * @ORM\ManyToOne(targetEntity=Videojuego::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $videojuego;

    /**
     * @ORM\Column(type="float")
     */
    private $descuento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidadCompra(): ?int
    {
        return $this->cantidadCompra;
    }

    public function setCantidadCompra(int $cantidadCompra): self
    {
        $this->cantidadCompra = $cantidadCompra;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPrecioVideojuego(): ?float
    {
        return $this->precioVideojuego;
    }

    public function setPrecioVideojuego(float $precioVideojuego): self
    {
        $this->precioVideojuego = $precioVideojuego;

        return $this;
    }

    public function getPedido(): ?Pedido
    {
        return $this->pedido;
    }

    public function setPedido(?Pedido $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }

    public function getVideojuego(): ?Videojuego
    {
        return $this->videojuego;
    }

    public function setVideojuego(?Videojuego $videojuego): self
    {
        $this->videojuego = $videojuego;

        return $this;
    }

    public function getDescuento(): ?float
    {
        return $this->descuento;
    }

    public function setDescuento(float $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }
}
