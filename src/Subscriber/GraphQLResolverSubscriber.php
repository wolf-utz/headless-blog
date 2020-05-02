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

namespace App\Subscriber;

use App\GraphQL\Resolver\CategoryResolver;
use App\GraphQL\Resolver\MutationResolver;
use App\GraphQL\Resolver\PostResolver;
use App\GraphQL\Resolver\QueryResolver;
use App\GraphQL\Resolver\TagResolver;
use OmegaCode\JwtSecuredApiGraphQL\Event\ResolverCollectedEvent;
use OmegaCode\JwtSecuredApiGraphQL\GraphQL\Resolver\ResolverInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GraphQLResolverSubscriber implements EventSubscriberInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResolverCollectedEvent::NAME => 'onCollected',
        ];
    }

    public function onCollected(ResolverCollectedEvent $event): void
    {
        $registry = $event->getResolverRegistry();
        $registry->clear();
        /** @var string $class */
        foreach ($this->getResolverClasses() as $class) {
            /** @var ResolverInterface $resolver */
            $resolver = $this->container->get($class);
            $registry->add($resolver, $resolver->getType());
        }
    }

    protected function getResolverClasses(): array
    {
        return [
            QueryResolver::class,
            PostResolver::class,
            TagResolver::class,
            CategoryResolver::class,
            MutationResolver::class,
        ];
    }
}
