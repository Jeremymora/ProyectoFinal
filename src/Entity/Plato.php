<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Plato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer')]
    private $id;

    #[ORM\Column(type:"string", length:255)]

    private $nombreDelPlato;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private $peso;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private $precio;

    #[ORM\Column(type:"integer")]
    private $kcal;

    #[ORM\Column(type:"boolean")]
    private $disponibilidad;
    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"plato")]
    private $detallesPedidos;

    #[ORM\OneToMany(targetEntity:"App\Entity\Carrito", mappedBy:"plato")]
    private $carritos;

    public function getId()
    {
        return $this->id;
    }
    public function getNombreDelPlato()
    {
        return $this->nombreDelPlato;
    }
    public function setNombreDelPlato($nombreDelPlato)
    {
        $this->nombreDelPlato = $nombreDelPlato;

        return $this;
    }
    public function getPeso()
    {
        return $this->peso;
    }
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }
    public function getKcal()
    {
        return $this->kcal;
    }
    public function setKcal($kcal)
    {
        $this->kcal = $kcal;

        return $this;
    }
    public function getDisponibilidad()
    {
        return $this->disponibilidad;
    }
    public function setDisponibilidad($disponibilidad)
    {
        $this->disponibilidad = $disponibilidad;

        return $this;
    }
}
