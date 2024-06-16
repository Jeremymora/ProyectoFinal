<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\PlatoRepository;

#[ORM\Entity(repositoryClass: "App\Repository\PlatoRepository")]
class Plato
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:'integer')]
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
    #[ORM\Column(type: 'string', length: 255)]
    private $image;
    #[ORM\OneToMany(targetEntity: DetallesPedido::class, mappedBy: 'plato')]
    private $detallesPedidos;

    #[ORM\OneToMany(targetEntity:"App\Entity\Carrito", mappedBy:"plato")]
    private $carritos;
    public function __construct()
    {
        $this->detallesPedidos = new ArrayCollection();
        $this->carritos = new ArrayCollection();
    }
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
    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
