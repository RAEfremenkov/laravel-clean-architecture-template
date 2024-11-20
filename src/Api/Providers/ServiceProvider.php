<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Api\Providers;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use RAEfremenkov\CleanArch\Infrastructure\Adapters\Doctrine\Types\GuidType;
use RAEfremenkov\CleanArch\Utils\Primitives\IEntityManager;
use RAEfremenkov\CleanArch\Utils\Primitives\IUnitOfWork;
use RAEfremenkov\CleanArch\Infrastructure\Adapters\Doctrine\UnitOfWork;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(
            base_path('src/Infrastructure/Adapters/LaravelDbContext/Migrations')
        );

        // Create schedule cron jobs here...

        // Register events...
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Doctrine
        $this->app->singleton(IEntityManager::class, function ($app) {
            $config = ORMSetup::createXMLMetadataConfiguration(
                paths: [$app->basePath('src/Infrastructure/Adapters/Doctrine/EntityConfigurations')],
                isDevMode: true,
            );

            $connection = DriverManager::getConnection(
                [
                    // Set DB connection here...
                ],
                $config
            );

            Type::addType('custom_guid', GuidType::class);

            $connection->getDatabasePlatform()->registerDoctrineTypeMapping('uuid', 'custom_guid');

            return new EntityManager($connection, $config);
        });

        // UnitOfWork
        $this->app->singleton(IUnitOfWork::class, function ($app) {
            $entityManager = $app->make(IEntityManager::class);

            return new UnitOfWork($entityManager);
        });

        // Bind domain event handlers here...

        // Bind repositories here...

        // Bind commands here...

        // Bind another services here...
    }
}
