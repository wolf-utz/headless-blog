<?php

declare(strict_types=1);

namespace App\GraphQL\Provider;

class SchemaProvider extends \OmegaCode\JwtSecuredApiGraphQL\GraphQL\Provider\SchemaProvider
{
    public const SCHEMA_FILE = APP_ROOT_PATH . 'res/schema.graphql';
}
