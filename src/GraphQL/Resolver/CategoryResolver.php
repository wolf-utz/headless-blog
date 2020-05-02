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

    /**
     * @param array $category
     *
     * {@inheritdoc}
     */
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

    protected function resolveParent(array $category, array $args, Context $context, ResolveInfo $info): ?array
    {
        return $this->categoryRepository->findOneByParentId((int) $category['id']);
    }
}
