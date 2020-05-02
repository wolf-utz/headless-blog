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

use Doctrine\DBAL\Query\QueryBuilder;
use OmegaCode\JwtSecuredApiCore\Service\DatabaseService;
use OmegaCode\JwtSecuredApiCore\Utility\StringUtility;

abstract class AbstractRepository
{
    protected DatabaseService $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function exists(int $id): bool
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $result = $queryBuilder
            ->select('COUNT(id) as count')
            ->from($this->getTable())
            ->where($queryBuilder->expr()->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute();
        $result = $statement instanceof \PDOStatement ? $statement->fetchAll() : [];

        return (int) ($result['count'] ?? 0) > 0;
    }

    public function findById(int $id): ?array
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from($this->getTable())
            ->where(
                $queryBuilder->expr()->eq('id', ':id')
            )
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->execute();
        $result = $statement instanceof \PDOStatement ? $statement->fetchAll() : [];

        return $result[0] ?? null;
    }

    public function findAll(): array
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from($this->getTable())
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }

    public function delete(int $id): int
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->delete($this->getTable())
            ->where(
                $queryBuilder->expr()->eq('id', ':id')
            )
            ->setParameter('id', $id)
            ->execute();

        return is_int($statement) ? $statement : 0;
    }

    abstract public function getTable(): string;

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->databaseService->getConnection()->createQueryBuilder();
    }

    protected function prepareInsertOrUpdateData(array $data): array
    {
        foreach ($data as $key => $value) {
            // Escape strings for database operations.
            if (is_string($value)) {
                $value = '"' . $value . '"';
                $data[$key] = $value;
            }
            // Convert camel case to snake case keys
            $newKey = StringUtility::camelCaseToSnakeCase($key);
            if ($newKey != $key) {
                $data[$newKey] = $data[$key];
                unset($data[$key]);
            }
        }

        return $data;
    }
}
