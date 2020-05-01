<?php

declare(strict_types=1);

namespace App\Repository;

class PostRepository extends AbstractRepository
{
    public function findAll(): array
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from('post')
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }

    public function findPaginated(int $page, int $first): array
    {
        $page = $page > 0 ? $page - 1 : $page;
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from('post')
            ->setFirstResult($page * $first)
            ->setMaxResults($first)
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }
}
