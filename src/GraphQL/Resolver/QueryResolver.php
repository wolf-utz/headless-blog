<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use App\Repository\PostRepository;
use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiCore\Utility\ArrayUtility;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class QueryResolver implements ResolverInterface
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function __invoke($root, array $args, Context $context, ResolveInfo $info)
    {
        switch ($info->fieldName) {
            case 'posts':
                return $this->resolvePosts($root, $args, $context, $info);
            default:
                return null;
        }
    }

    public function getType(): string
    {
        return 'Query';
    }

    protected function resolvePosts($root, array $args, Context $context, ResolveInfo $info)
    {
        $data = $this->postRepository->findPaginated((int) $args['page'], (int) $args['first']);
        foreach ($data as &$row) {
            $row = ArrayUtility::snakeCaseKeysToCamelCaseKeys($row);
        }

        return $data;
    }
}
