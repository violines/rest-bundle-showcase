<?php

namespace App\Domain\User;

use App\Domain\User\Command\CreateProfile;
use App\Domain\User\PasswordEncoder;
use App\Domain\User\Value\Email;
use App\Domain\User\Value\Password;
use App\Domain\User\Value\UserId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\UserDoctrineRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    private $roles;

    private function __construct(
        UserId $userId,
        Email $email,
        Password $password,
        PasswordEncoder $passwordEncoder,
        array $roles = []
    ) {
        $this->id = $userId->toInt();
        $this->email = $email->toString();
        $this->password = $passwordEncoder->encode($this, $password->toString());
        $this->key = self::generateKey();
        $this->roles = $roles;
    }

    public static function fromCreateProfile(UserId $userId, CreateProfile $profile, PasswordEncoder $passwordEncoder): self
    {
        return new self($userId, Email::fromString($profile->email), Password::fromString($profile->password), $passwordEncoder);
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email->toString();
    }

    public function resetPassword(Password $password, PasswordEncoder $passwordEncoder): void
    {
        $this->password = $passwordEncoder->encode($this, $password->toString());
    }

    public function changeRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function resetKey(): void
    {
        $this->key = self::generateKey();
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
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public static function generateKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }
}
