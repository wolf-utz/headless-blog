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

class CategoryRepository extends AbstractRepository
{
    public function findOneByParentId(int $parentId): ?array
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from($this->getTable())
            ->where($queryBuilder->expr()->eq('id', ':parentId'))
            ->setParameter('parentId', $parentId)
            ->setMaxResults(1)
            ->execute();

        $result = $statement instanceof \PDOStatement ? $statement->fetchAll() : [];

        return $result[0] ?? null;
    }

    public function findByPostId(int $postId): array
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from($this->getTable())
            ->innerJoin($this->getTable(), 'post_category', 'relation', 'relation.category_id = category.id')
            ->where($queryBuilder->expr()->eq('relation.post_id', ':postId'))
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
