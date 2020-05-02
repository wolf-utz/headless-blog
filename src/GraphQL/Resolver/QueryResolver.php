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
                return $this->resolvePosts($args, $context, $info);
            default:
                return null;
        }
    }

    public function getType(): string
    {
        return 'Query';
    }

    protected function resolvePosts(array $args, Context $context, ResolveInfo $info): array
    {
        $data = $this->postRepository->findPaginated((int) $args['page'], (int) $args['first']);
        foreach ($data as &$row) {
            $row = ArrayUtility::snakeCaseKeysToCamelCaseKeys($row);
        }

        return $data;
    }
}
