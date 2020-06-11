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

    public static function fromProfile(ProfileWrite $profile): self
    {
        return new self($profile->email);
    }

    public function toFrontendDTO(): ProfileRead
    {
        return new ProfileRead($this->email, $this->roles, $this->key);
    }

    public function toAdminDTO(): AdminUser
    {
        return new AdminUser($this->email, $this->roles, $this->key);
    }

    public function adminEdit(AdminUser $adminUser): self
    {
        if ($adminUser->isResetKey) {
            $this->key = $this->generateKey();
        }

        $this->email = $adminUser->email;
        $this->roles = $adminUser->roles;

        return $this;
    }

    public function resetPassword(UserPasswordEncoderInterface $passwordEncoder, ProfileWrite $profile): void
    {
        $this->password = $passwordEncoder->encodePassword($this, $profile->password);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
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

    private function generateKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(8));
    }
}
