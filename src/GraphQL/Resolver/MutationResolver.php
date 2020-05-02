<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

use App\Exception\Repository\RecordNotFoundException;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use GraphQL\Type\Definition\ResolveInfo;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Context;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;

class MutationResolver implements ResolverInterface
{
    protected PostRepository $postRepository;

    protected CategoryRepository $categoryRepository;

    protected TagRepository $tagRepository;

    public function __construct(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function __invoke($root, array $args, Context $context, ResolveInfo $info)
    {
        if (method_exists($this, 'resolve' . ucfirst($info->fieldName))) {
            $method = 'resolve' . ucfirst($info->fieldName);

            return $this->$method($root, $args, $context, $info);
        }

        return null;
    }

    public function getType(): string
    {
        return 'Mutation';
    }

    protected function resolveCreatePost($root, array $args, Context $context, ResolveInfo $info): array
    {
        $newId = $this->postRepository->insert($args['postInput']);

        return $this->postRepository->findById($newId);
    }

    protected function resolveCreateCategory($root, array $args, Context $context, ResolveInfo $info): array
    {
        $newId = $this->categoryRepository->insert($args['categoryInput']);

        return $this->categoryRepository->findById($newId);
    }

    protected function resolveCreateTag($root, array $args, Context $context, ResolveInfo $info): array
    {
        if (!is_null($this->tagRepository->findByTitle($args['tagInput']['title']))) {
            throw new \InvalidArgumentException('tag with title "' . $args['tagInput']['title'] . '" already exist');
        }
        $newId = $this->tagRepository->insert($args['tagInput']);

        return $this->tagRepository->findById($newId);
    }

    protected function resolveDeletePost($root, array $args, Context $context, ResolveInfo $info): bool
    {
        return (bool) $this->postRepository->delete((int) $args['id']);
    }

    protected function resolveDeleteCategory($root, array $args, Context $context, ResolveInfo $info): bool
    {
        return (bool) $this->categoryRepository->delete((int) $args['id']);
    }

    protected function resolveDeleteTag($root, array $args, Context $context, ResolveInfo $info): bool
    {
        return (bool) $this->tagRepository->delete((int) $args['id']);
    }

    protected function resolveUpdatePost($root, array $args, Context $context, ResolveInfo $info): array
    {
        if (!$this->postRepository->exists((int) $args['id'])) {
            throw new RecordNotFoundException('Post with id "' . $args['id'] . '" does not exist');
        }
        $this->postRepository->update((int) $args['id'], $args['postInput']);

        return $this->postRepository->findById((int) $args['id']);
    }

    protected function resolveUpdateCategory($root, array $args, Context $context, ResolveInfo $info): array
    {
        if (!$this->categoryRepository->exists((int) $args['id'])) {
            throw new RecordNotFoundException('Category with id "' . $args['id'] . '" does not exist');
        }
        $this->categoryRepository->update((int) $args['id'], $args['categoryInput']);

        return $this->categoryRepository->findById((int) $args['id']);
    }

    protected function resolveUpdateTag($root, array $args, Context $context, ResolveInfo $info): array
    {
        if (!$this->tagRepository->exists((int) $args['id'])) {
            throw new RecordNotFoundException('Tag with id "' . $args['id'] . '" does not exist');
        }
        $this->tagRepository->update((int) $args['id'], $args['tagInput']);

        return $this->tagRepository->findById((int) $args['id']);
    }
}
