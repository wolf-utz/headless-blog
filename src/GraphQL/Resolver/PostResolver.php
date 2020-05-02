<?php

/**
 * MIT License
 *
 * Copyright (c) 2020 Wolf Utz<wpu@hotmail.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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

    protected function resolveCategories(array $post, array $args, Context $context, ResolveInfo $info): array
    {
        return $this->categoryRepository->findByPostId((int) $post['id']);
    }

    protected function resolveTags(array $post, array $args, Context $context, ResolveInfo $info): array
    {
        return $this->tagRepository->findByPostId((int) $post['id']);
    }

    protected function resolveDateTime(array $post, array $args, Context $context, ResolveInfo $info): int
    {
        $time = strtotime($post[$info->fieldName]);

        return $time === false ? 0 : $time;
    }
}
