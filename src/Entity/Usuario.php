<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $nombreDeUsuario;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $email;

    #[ORM\Column(type:'string', length: 255)]
    private ?string $contrasenia;

    #[ORM\OneToMany(targetEntity:"App\Entity\Pedidos", mappedBy:"usuario")]

    private $pedidos;

    #[ORM\OneToMany(targetEntity:"App\Entity\Carrito", mappedBy:"usuario")]

    private $carritos;

    #[ORM\OneToMany(targetEntity:"App\Entity\DetallesPedido", mappedBy:"usuario")]
    private $detallesPedido;

    #[ORM\Column(type:'boolean')]
    private ?bool $IsVerified = false;

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
        // Devuelve los roles que tiene este usuario. Por ahora, solo devolveremos 'ROLE_USER'.
        return ['ROLE_USER'];
    }
    public function eraseCredentials()
    {
        // Este método se puede dejar vacío, a menos que tengas datos sensibles que quieras borrar/limpiar.
    }

    public function getPassword(): string
    {
        return $this->getContrasenia();
    }
    public function getUserIdentifier(): string
    {
        return "";
    }
    public function getIsVerified(): ?bool
    {
        return $this->IsVerified;
    }

    public function setIsVerified(bool $IsVerified): self
    {
        $this->IsVerified = $IsVerified;

        return $this;
    }
}
