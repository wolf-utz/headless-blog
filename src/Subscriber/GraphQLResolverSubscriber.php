<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\GraphQL\Resolver\QueryResolver;
use OmegaCode\JwtSecuredApiGraphQL\Event\ResolverCollectedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GraphQLResolverSubscriber implements EventSubscriberInterface
{
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
        $queryResolver = new QueryResolver();
        $registry->addResolver($queryResolver);
    }
}
