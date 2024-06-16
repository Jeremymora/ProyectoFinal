<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $nombreDeUsuario;

    #[ORM\Column(type:'string', length: 255, unique: true)]
    private $email;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $contrasenia;

    #[ORM\OneToMany(targetEntity:"App\Entity\Pedidos", mappedBy:"usuario", cascade: ['persist', 'remove'])]

    private $pedidos;

    #[ORM\OneToMany(targetEntity:"App\Entity\Carrito", mappedBy:"usuario")]

    private $carritos;

    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"usuario")]
    private $detallesPedido;

    #[ORM\Column(type:'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreDeUsuario(): ?string
    {
        return $this->nombreDeUsuario;
    }

    public function setNombreDeUsuario(string $nombreDeUsuario): self
    {
        $this->nombreDeUsuario = $nombreDeUsuario;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContrasenia(): string
    {
        return (string) $this->contrasenia;
    }

    public function setContrasenia(string $contrasenia): self
    {
        $this->contrasenia = $contrasenia;

        return $this;
    }
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
    public function eraseCredentials(): void
    {
    }

    public function getPassword(): string
    {
        return $this->getContrasenia();
    }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function IsVerified(): ?bool
    {
        return $this->isVerified;
    }
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedidos $pedido): self
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos[] = $pedido;
            $pedido->setUsuario($this);
        }

        return $this;
    }
    public function removePedido(Pedidos $pedido): self
    {
        if ($this->pedidos->contains($pedido)) {
            $this->pedidos->removeElement($pedido);
            // set the owning side to null (unless already changed)
            if ($pedido->getUsuario() === $this) {
                $pedido->setUsuario(null);
            }
        }

        return $this;
    }
}
