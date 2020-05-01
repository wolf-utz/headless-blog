<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class TagResolver implements ResolverInterface
{
    public function __invoke($tag, array $args, Context $context, ResolveInfo $info)
    {
        return $tag[$info->fieldName] ?? null;
    }

    public function getType(): string
    {
        return 'Tag';
    }
}
