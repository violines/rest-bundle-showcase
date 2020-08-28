<?php

namespace App\Entity;

use App\DTO\Admin\User as AdminUser;
use App\DTO\Frontend\ProfileRead;
use App\DTO\Frontend\ProfileWrite;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @var array
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

    public static function fromProfile(ProfileWrite $profile, UserPasswordEncoderInterface $passwordEncoder): self
    {
        $user = new self($profile->email);
        $user->resetPassword($passwordEncoder, $profile->password);
        return $user;
    }

    public function toFrontendDTO(): ProfileRead
    {
        return new ProfileRead($this->email, $this->roles, $this->key);
    }

    public function toAdminDTO(): AdminUser
    {
        return new AdminUser($this->email, $this->roles, $this->key);
    }

    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    public function resetPassword(UserPasswordEncoderInterface $passwordEncoder, string $password): void
    {
        $this->password = $passwordEncoder->encodePassword($this, $password);
    }

    public function changeRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function resetKey(): void
    {
        $this->key = bin2hex(openssl_random_pseudo_bytes(8));
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}
