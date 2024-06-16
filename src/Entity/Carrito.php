<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarritoRepository;

#[ORM\Entity(repositoryClass: CarritoRepository::class)]
class Carrito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;
    #[ORM\ManyToOne(targetEntity: Pedidos::class, inversedBy: 'carritos')]
    #[ORM\JoinColumn(nullable: false)]
    private $pedido;
    #[ORM\Column(type:"integer")]
    private $cantidad;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private $precioTotal;
    #[ORM\ManyToOne(targetEntity:"App\Entity\Usuario", inversedBy:"carritos")]
    #[ORM\JoinColumn(nullable:false)]
    private $usuario;
    #[ORM\ManyToOne(targetEntity:"App\Entity\Plato", inversedBy:"carritos")]
    #[ORM\JoinColumn(nullable:false)]
    private $plato;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }

    public function setPedido(?Pedidos $pedido): self
    {
        $this->pedido = $pedido;
        return $this;
    }
    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }
    public function getPrecioTotal(): ?string
    {
        return $this->precioTotal;
    }

    public function setPrecioTotal(string $precioTotal): self
    {
        $this->precioTotal = $precioTotal;

        return $this;
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
    public function getPlato(): ?Plato
    {
        return $this->plato;
    }

    public function setPlato(?Plato $plato): self
    {
        $this->plato = $plato;

        return $this;
    }
}
