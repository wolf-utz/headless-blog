<?php

declare(strict_types=1);

namespace App\Repository;

class PostRepository extends AbstractRepository
{
    public function findPaginated(int $page, int $first): array
    {
        $page = $page > 0 ? $page - 1 : $page;
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from($this->getTable())
            ->setFirstResult($page * $first)
            ->setMaxResults($first)
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }

    /**
     * @return int the new record id
     */
    public function insert(array $data): int
    {
        $data = $this->prepareInsertOrUpdateData($data);
        $categoryIds = $data['categories'] ?? [];
        $tagIds = $data['tags'] ?? [];
        unset($data['categories']);
        unset($data['tags']);

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->insert($this->getTable())
            ->values($data)
            ->execute();

        $newId = (int) $queryBuilder->getConnection()->lastInsertId($this->getTable());
        if (count($categoryIds) > 0) {
            $this->insertCategoryRelations($newId, $categoryIds);
        }
        if (count($tagIds) > 0) {
            $this->insertTagRelations($newId, $tagIds);
        }

        return $newId;
    }

    public function update(int $id, array $data): void
    {
        $data = $this->prepareInsertOrUpdateData($data);
        $categoryIds = $data['categories'] ?? [];
        $tagIds = $data['tags'] ?? [];
        unset($data['categories']);
        unset($data['tags']);

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->update($this->getTable());
        foreach ($data as $key => $value) {
            $queryBuilder->set($key, $value);
        }
        $queryBuilder->where($queryBuilder->expr()->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute();
        if (count($categoryIds) > 0) {
            $this->insertCategoryRelations($id, $categoryIds);
        }
        if (count($tagIds) > 0) {
            $this->insertTagRelations($id, $tagIds);
        }
    }

    public function getTable(): string
    {
        return 'post';
    }

    protected function insertCategoryRelations(int $id, array $categoryIds)
    {
        $this->removeCategoryRelations($id);
        foreach ($categoryIds as $categoryId) {
            $queryBuilder = $this->createQueryBuilder();
            $queryBuilder->insert('post_category')
                ->values([
                    'post_id' => ':postId',
                    'category_id' => ':categoryId',
                ])
                ->setParameter('postId', $id)
                ->setParameter('categoryId', $categoryId)
                ->execute();
        }
    }

    protected function insertTagRelations(int $id, array $tagIds)
    {
        $this->removeTagRelations($id);
        foreach ($tagIds as $tagId) {
            $queryBuilder = $this->createQueryBuilder();
            $queryBuilder->insert('post_tag')
                ->values([
                    'post_id' => ':postId',
                    'tag_id' => ':tagId',
                ])
                ->setParameter('postId', $id)
                ->setParameter('tagId', $tagId)
                ->execute();
        }
    }

    protected function removeCategoryRelations(int $id)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete('post_category')
            ->where($queryBuilder->expr()->eq('post_id', ':postId'))
            ->setParameter('postId', $id)
            ->execute();
    }

    protected function removeTagRelations(int $id)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete('post_tag')
            ->where($queryBuilder->expr()->eq('post_id', ':postId'))
            ->setParameter('postId', $id)
            ->execute();
    }
}
