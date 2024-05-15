<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Usuario", inversedBy:"pedidos")]
    #[ORM\JoinColumn(nullable:false)]
    private $usuario;

    #[ORM\Column(type:"date")]
    private $fechaDelPedido;

    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"pedido")]
    private $detallesPedidos;

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
}
