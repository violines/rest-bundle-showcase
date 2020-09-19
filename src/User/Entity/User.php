<?php

namespace App\User\Entity;

use App\User\Command\CreateProfile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UserRepository")
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
     * @ORM\OneToMany(targetEntity="App\Review\Entity\Review", mappedBy="user")
     */
    private $reviews;

    private function __construct(
        string $email,
        string $password,
        UserPasswordEncoderInterface $passwordEncoder,
        ?string $key = null,
        array $roles = []
    ) {
        $this->email = $email;
        $this->password = $passwordEncoder->encodePassword($this, $password);
        $this->key = $key;
        $this->roles = $roles;
        $this->reviews = new ArrayCollection();
    }

    public static function fromCreateProfile(CreateProfile $profile, UserPasswordEncoderInterface $passwordEncoder): self
    {
        return new self($profile->email, $profile->password, $passwordEncoder);
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getKey(): ?string
    {
        return $this->key;
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
}
