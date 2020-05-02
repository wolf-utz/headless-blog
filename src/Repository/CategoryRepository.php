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
            ->from($this->getTable())
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
            ->from($this->getTable())
            ->innerJoin($this->getTable(), 'post_category', 'relation', 'relation.category_id = category.id')
            ->where(
                $queryBuilder->expr()->eq('relation.post_id', ':postId')
            )
            ->setParameter('postId', $postId)
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }

    /**
     * @return int the new record id
     */
    public function insert(array $data): int
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->insert($this->getTable())
            ->values($this->prepareInsertOrUpdateData($data))
            ->execute();

        return (int) $queryBuilder->getConnection()->lastInsertId($this->getTable());
    }

    public function update(int $id, array $data): void
    {
        $data = $this->prepareInsertOrUpdateData($data);
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->update($this->getTable());
        foreach ($data as $key => $value) {
            $queryBuilder->set($key, $value);
        }
        $queryBuilder
            ->where($queryBuilder->expr()->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute();
    }

    public function getTable(): string
    {
        return 'category';
    }
}
