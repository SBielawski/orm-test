<?php
declare(strict_types=1);

namespace Cycle\Orm\Repository;

use Cycle\ORM\Select;

final class UserRepository extends Select\Repository
{
    public function findAllWithLimit(int $limit): array
    {
        return $this->select()->limit($limit)->fetchAll();
    }
}
