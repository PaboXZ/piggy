<?php

declare(strict_types=1);

use Framework\{Container, TemplateEngine, Database};
use App\Services\{TransactionService, ValidatorService, UserService};
use App\Config\Paths;

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEW),
    ValidatorService::class => fn() => new ValidatorService,
    Database::class => fn() => new Database($_ENV['DB_DRIVER'], [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME']
    ], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']),
    UserService::class => function (Container $container) {
        $database = $container->get(Database::class);
        return new UserService($database);
    },
    TransactionService::class => function(Container $container) {
        $database = $container->get(Database::class);
        return new TransactionService($database);
    }
];