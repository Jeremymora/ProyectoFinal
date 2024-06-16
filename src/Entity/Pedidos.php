<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable:false)]
    private $usuario;

    #[ORM\Column(type:"date")]
    private $fechaDelPedido;

    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"pedido", cascade: ['persist', 'remove'])]
    private $detallesPedidos;
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $totalPrecio;
    #[ORM\Column(type: 'string', length: 20)]
    private $status = 'pending';
    #[ORM\OneToMany(targetEntity: Carrito::class, mappedBy: 'pedido', cascade: ['persist', 'remove'])]
    private $carritos;

    public function __construct()
    {
        $this->detallesPedidos = new ArrayCollection();
        $this->carritos = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
    public function getFechaDelPedido(): ?\DateTimeInterface
    {
        return $this->fechaDelPedido;
    }

    public function setFechaDelPedido(?\DateTimeInterface $fechaDelPedido): self
    {
        $this->fechaDelPedido = $fechaDelPedido;

        return $this;
    }
    public function getTotalPrecio(): ?float
    {
        return $this->totalPrecio;
    }

    public function setTotalPrecio(?float $totalPrecio): self
    {
        $this->totalPrecio = $totalPrecio;

        return $this;
    }
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
    public function getDetallesPedidos()
    {
        return $this->detallesPedidos;
    }
    public function setDetallesPedidos($detallesPedidos)
    {
        $this->detallesPedidos = $detallesPedidos;

        return $this;
    }
    public function getCarritos(): Collection
    {
        return $this->carritos;
    }

    public function addCarrito(Carrito $carrito): self
    {
        if (!$this->carritos->contains($carrito)) {
            $this->carritos[] = $carrito;
            $carrito->setPedido($this);
        }

        return $this;
    }

    public function removeCarrito(Carrito $carrito): self
    {
        if ($this->carritos->removeElement($carrito)) {
            if ($carrito->getPedido() === $this) {
                $carrito->setPedido(null);
            }
        }

        return $this;
    }
}
