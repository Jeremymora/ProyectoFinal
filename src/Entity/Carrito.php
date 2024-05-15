<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Carrito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"integer")]
    private $idPedido;

    #[ORM\Column(type:"integer")]
    private $idPlato;

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
    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }

    public function setIdPedido(int $idPedido): self
    {
        $this->idPedido = $idPedido;

        return $this;
    }
    public function getIdPlato(): ?int
    {
        return $this->idPlato;
    }

    public function setIdPlato(int $idPlato): self
    {
        $this->idPlato = $idPlato;

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
}
