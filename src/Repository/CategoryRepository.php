<?php

declare(strict_types=1);

namespace App\Repository;

class CategoryRepository extends AbstractRepository
{
    public function findOneByParentId(int $parentId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from('category')
            ->where(
                $queryBuilder->expr()->eq('id', ':parentId')
            )
            ->setParameter('parentId', $parentId)
            ->setMaxResults(1)
            ->execute();

        $result = $statement instanceof \PDOStatement ? $statement->fetchAll() : [];

        return $result[0] ?? null;
    }

    public function findByPostId(int $postId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from('category')
            ->innerJoin('category', 'post_category', 'relation', 'relation.category_id = category.id')
            ->where(
                $queryBuilder->expr()->eq('relation.post_id', ':postId')
            )
            ->setParameter('postId', $postId)
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }
}
