<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Infrastructure\Adapters\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use RAEfremenkov\CleanArch\Utils\Primitives\IAggregateRoot;
use RAEfremenkov\CleanArch\Utils\Primitives\INotificationHandler;
use RAEfremenkov\CleanArch\Utils\Primitives\IUnitOfWork;

class UnitOfWork implements IUnitOfWork
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        //
    }

    public function saveChanges(): bool
    {
        $scheduledEntities = array_merge(
            $this->entityManager->getUnitOfWork()->getScheduledEntityInsertions(),
            $this->entityManager->getUnitOfWork()->getScheduledEntityUpdates(),
            $this->entityManager->getUnitOfWork()->getScheduledEntityDeletions()
        );

        $domainEvents = [];
        foreach ($scheduledEntities as $entity) {
            if (! $entity instanceof IAggregateRoot) {
                continue;
            }

            $domainEvents = array_merge($domainEvents, $entity->getDomainEvents());
            $entity->clearDomainEvents();
        }

        $this->entityManager->flush();

        foreach ($domainEvents as $domainEvent) {
            /** @var INotificationHandler|null $eventHandler */
            $eventHandler = rescue(
                fn () => app($domainEvent::class),
                report: false
            );

            if (! $eventHandler) {
                continue;
            }

            $eventHandler->handle($domainEvent);
        }

        return true;
    }
}
