<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;

   /**
    *
    * @ApiResource()
    * @ORM\Table(name="app_users")
    * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
    */
class User implements UserInterface, \Serializable
{
        /**
        * @ORM\Column(type="integer")
        * @ORM\Id
        * @ORM\GeneratedValue(strategy="AUTO")
        */
    private $id;

        /**
        * @ORM\Column(name="username",type="string", length=25, unique=true)
        */
    private $username;


    /**
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    private $roles = [];


    /**
    * @ORM\Column(type="string", length=64)
    */
    private $password;

        /**
        * @ORM\Column(type="string", length=254, unique=true)
        */
    private $email;

        /**
        * @ORM\Column(name="is_active", type="boolean")
        */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
        $this->id,
        $this->username,
        $this->password,
        // see section on salt below
        // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
        $this->id,
        $this->username,
        $this->password,
        // see section on salt below
        // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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



    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

}
