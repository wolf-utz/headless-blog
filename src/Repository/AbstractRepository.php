<?php declare(strict_types=1);

namespace App\Repository;

use App\Utility\StringUtility;
use OmegaCode\JwtSecuredApiCore\Service\DatabaseService;

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
        $result = $queryBuilder
            ->select('COUNT(id) as count')
            ->from($this->getTable())
            ->where($queryBuilder->expr()->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

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

        return $queryBuilder
            ->delete($this->getTable())
            ->where(
                $queryBuilder->expr()->eq('id', ':id')
            )
            ->setParameter('id', $id)
            ->execute();
    }

    abstract public function getTable(): string;

    protected function createQueryBuilder()
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
