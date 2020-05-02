<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\GraphQL\Resolver\CategoryResolver;
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

    public function onCollected(ResolverCollectedEvent $event)
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
        ];
    }
}
