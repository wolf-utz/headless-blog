<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class PostResolver implements ResolverInterface
{
    protected CategoryRepository $categoryRepository;

    protected TagRepository $tagRepository;

    public function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function __invoke($post, array $args, Context $context, ResolveInfo $info)
    {
        switch ($info->fieldName) {
            case 'categories':
                return $this->resolveCategories($post, $args, $context, $info);
            case 'tags':
                return $this->resolveTags($post, $args, $context, $info);
            case 'createdAt':
            case 'updatedAt':
                return $this->resolveDateTime($post, $args, $context, $info);
            default:
                return $post[$info->fieldName] ?? null;
        }
    }

    public function getType(): string
    {
        return 'Post';
    }

    protected function resolveCategories($post, array $args, Context $context, ResolveInfo $info): array
    {
        return $this->categoryRepository->findByPostId((int) $post['id']);
    }

    protected function resolveTags($post, array $args, Context $context, ResolveInfo $info): array
    {
        return $this->tagRepository->findByPostId((int) $post['id']);
    }

    protected function resolveDateTime($post, array $args, Context $context, ResolveInfo $info)
    {
        return strtotime($post[$info->fieldName]);
    }
}
