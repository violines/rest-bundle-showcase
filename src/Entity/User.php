<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"default"="nextval('user_id_seq'::regclass)"})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="user_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $key;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct(string $email, array $roles)
    {
        $this->email = $email;

        if (in_array('ROLE_IMPORT', $roles)) {
            $this->key = $this->generateKey();
        }

        $this->roles = $roles;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRoles(array $roles): void
    {
        $this->roles = array_unique(array_merge($this->roles, $roles));
    }

    public function removeRoles(array $removeRoles): void
    {
        foreach ($this->roles as $key => $role) {
            if (in_array($role, $removeRoles)) {
                unset($this->roles[$key]);
            }
        }
    }

    private function generateKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }
}
