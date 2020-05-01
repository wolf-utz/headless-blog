<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class QueryResolver implements ResolverInterface
{
    public function __invoke($root, array $args, Context $context, ResolveInfo $info): ?string
    {
        if ($info->fieldName === 'greet') {
            $name = strip_tags($args['name']);

            return "Hello $name";
        }
        if ($info->fieldName === 'multiply') {
            $a = (int) $args['a'];
            $b = (int) $args['b'];

            return (string) ($a * $b);
        }

        return null;
    }

    public function getType(): string
    {
        return 'Query';
    }
}
