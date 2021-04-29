<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(
 *     name="api_token",
 *     indexes={
 *         @ORM\Index(name="api_token__user_id__ind", columns={"user_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ApiToken
{
    use StatusTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?UserInterface $user;

    public function __construct()
    {
        $this->token = bin2hex(random_bytes(60));
        $this->expiresAt = new DateTime('+365 day');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?DateTime
    {
        return $this->expiresAt;
    }
    public function setExpiresAt(DateTime $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new DateTime();
    }

    public function renewExpiresAt()
    {
        $this->expiresAt = new DateTime('+365 day');
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }
}
