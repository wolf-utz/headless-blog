<?php

/**
 * MIT License
 *
 * Copyright (c) 2020 Wolf Utz<wpu@hotmail.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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

    protected function insertCategoryRelations(int $id, array $categoryIds): void
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

    protected function insertTagRelations(int $id, array $tagIds): void
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

    protected function removeCategoryRelations(int $id): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete('post_category')
            ->where($queryBuilder->expr()->eq('post_id', ':postId'))
            ->setParameter('postId', $id)
            ->execute();
    }

    protected function removeTagRelations(int $id): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete('post_tag')
            ->where($queryBuilder->expr()->eq('post_id', ':postId'))
            ->setParameter('postId', $id)
            ->execute();
    }
}
