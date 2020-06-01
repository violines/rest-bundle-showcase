<?php

namespace App\Entity;

use App\DTO\Admin\User as AdminUser;
use App\DTO\Frontend\ProfileRead;
use App\DTO\Frontend\ProfileWrite;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=180)
     */
    private $password;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $key;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="user")
     */
    private $reviews;

    private function __construct(string $email)
    {
        $this->email = $email;
        $this->reviews = new ArrayCollection();
    }

    public static function fromProfile(ProfileWrite $profile): self
    {
        return new self($profile->email);
    }

    public function toProfile(): ProfileRead
    {
        return new ProfileRead(
            $this->email,
            $this->roles,
            $this->key
        );
    }

    public function toAdminUser(): AdminUser
    {
        $user = new AdminUser();

        $user->email = $this->email;
        $user->key = $this->key;
        $user->roles = $this->roles;

        return $user;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function resetPassword(string $encodedPassword): void
    {
        $this->password = $encodedPassword;
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
        $this->roles = array_values(array_unique(array_merge($this->roles, $roles)));
    }

    public function removeRoles(array $removeRoles): void
    {
        foreach ($this->roles as $key => $role) {
            if (in_array($role, $removeRoles)) {
                unset($this->roles[$key]);
            }
        }
    }

    public function resetKey(): void
    {
        $this->key = $this->generateKey();
    }

    private function generateKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }
}
