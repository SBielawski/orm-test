<?php
declare(strict_types=1);

namespace Doctrine\Orm\Repository;

use Doctrine\ORM\EntityRepository;

final class UserRepository extends EntityRepository
{
    public function findAllWithLimit(int $count): array
    {
        return $this->createQueryBuilder('u')->select()->setMaxResults($count)->getQuery()->getResult();
    }
}
