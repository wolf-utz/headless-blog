<?php

namespace App\Exception\Repository;

use GraphQL\Error\ClientAware;

class RecordNotFoundException extends \Exception implements ClientAware
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return "database";
    }
}