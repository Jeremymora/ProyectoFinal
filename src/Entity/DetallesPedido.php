<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pedidos;

#[ORM\Entity]
class DetallesPedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"integer")]
    private $cantidad;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private $precioTotal;

    #[ORM\ManyToOne(targetEntity:Pedidos::class, inversedBy:"detallesPedidos")]
    #[ORM\JoinColumn(nullable:false)]
    private $pedido;

    #[ORM\ManyToOne(targetEntity: Plato::class, inversedBy: 'detallesPedidos')]
    #[ORM\JoinColumn(nullable:false)]
    private $plato;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Usuario", inversedBy:"detallesPedido")]
    #[ORM\JoinColumn(nullable:false)]
    private $usuario;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }

    public function setPedido(?Pedidos $pedido): self
    {
        $this->pedido = $pedido;

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
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
