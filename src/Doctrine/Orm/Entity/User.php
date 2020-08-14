<?php
declare(strict_types=1);

namespace Doctrine\Orm\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass="Doctrine\Orm\Repository\UserRepository")
 * @ORM\Table(name="public.user")
 */
final class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected string $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected string $lastName;

    /**
     * @ORM\Column(type="string")
     */
    protected string $email;

    /**
     * @ORM\Column(type="string")
     */
    protected string $createdAt;

    /**
     * @ORM\Column(type="string")
     */
    protected string $updatedAt;

    public function __construct(
        Uuid $id,
        string $firstName,
        string $lastName,
        string $email,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = (string) $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt->format('Y-m-d H:i:s');
    }
}
