<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PedidosRepository;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(nullable:false)]
    private $usuario;

    #[ORM\Column(type:"date")]
    private $fechaDelPedido;

    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"pedido")]
    private $detallesPedidos;
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $totalPrecio;

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
}
