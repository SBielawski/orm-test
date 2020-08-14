<?php
declare(strict_types=1);

namespace Cycle\Orm\Entity;

use Cycle\Annotated\Annotation as Cycle;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @Cycle\Entity(
 *     role = "user",
 *     table = "public.user"
 * )
 */
final class User
{
    /**
     * @Cycle\Column(type="primary")
     */
    protected string $id;

    /**
     * @Cycle\Column(type="string")
     */
    protected string $firstName;

    /**
     * @Cycle\Column(type="string")
     */
    protected string $lastName;

    /**
     * @Cycle\Column(type="string")
     */
    protected string $email;

    /**
     * @Cycle\Column(type="string")
     */
    protected string $createdAt;

    /**
     * @Cycle\Column(type="string")
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
