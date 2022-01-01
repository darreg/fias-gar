<?php

namespace App\Shared\Infrastructure\Symfony;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

final class MigrationEventSubscriber implements EventSubscriber
{
    /**
     * @return array<int, string>
     */
    public function getSubscribedEvents(): array
    {
        return ['postGenerateSchema'];
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
