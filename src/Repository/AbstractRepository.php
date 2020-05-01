<?php declare(strict_types=1);

namespace App\Repository;

use OmegaCode\JwtSecuredApiCore\Service\DatabaseService;

abstract class AbstractRepository
{
    protected DatabaseService $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    protected function createQueryBuilder()
    {
        return $this->databaseService->getConnection()->createQueryBuilder();
    }
}
