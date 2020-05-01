<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use App\Repository\CategoryRepository;
use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class CategoryResolver implements ResolverInterface
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke($category, array $args, Context $context, ResolveInfo $info)
    {
        switch ($info->fieldName) {
            case 'parent':
                return $this->resolveParent($category, $args, $context, $info);
            default:
                return $category[$info->fieldName] ?? null;
        }
    }

    public function getType(): string
    {
        return 'Category';
    }

    protected function resolveParent($category, array $args, Context $context, ResolveInfo $info): array
    {
        return $this->categoryRepository->findOneByParentId((int) $category['id']);
    }
}
