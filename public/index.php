<?php

use Mhr\EventSourcePhp\Command\CreateAccountCommand;
use Mhr\EventSourcePhp\EventHandler\AccountWasCreatedEventHandler;
use Mhr\EventSourcePhp\EventStore\DBALEventStore;
use Mhr\EventSourcePhp\Repository\AccountRepository;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

require dirname(__DIR__).'/vendor/autoload.php';

$connectionParams = array(
    'url' => 'sqlite:///events.sqlite',
);

$connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
$dbalEventStore = new DBALEventStore($connection);


$eventBus = new \Symfony\Component\Messenger\MessageBus([
    new HandleMessageMiddleware(new HandlersLocator([
        \Mhr\EventSourcePhp\Event\AccountWasCreatedEvent::class => [
            new AccountWasCreatedEventHandler()
        ],
    ])),
]);

$accountRepository = new AccountRepository($dbalEventStore, $eventBus);

$handler = new \Mhr\EventSourcePhp\CommandHandler\AccountHandler($accountRepository);

$commandBus = new \Symfony\Component\Messenger\MessageBus([
    new HandleMessageMiddleware(new HandlersLocator([
        CreateAccountCommand::class => [$handler],
    ])),
]);

$commandBus->dispatch(new CreateAccountCommand(/* ... */));

