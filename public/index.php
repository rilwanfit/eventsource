<?php

use Mhr\EventSourcePhp\Command\AmountWasDepositedCommand;
use Mhr\EventSourcePhp\Command\CreateAccountCommand;
use Mhr\EventSourcePhp\EventHandler\AccountWasCreatedEventHandler;
use Mhr\EventSourcePhp\EventStore\DBALEventStore;
use Mhr\EventSourcePhp\Repository\AccountRepository;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

require dirname(__DIR__).'/vendor/autoload.php';

// Cool kids love debuging
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


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

$accountRepository = new AccountRepository($dbalEventStore, $eventBus, new \Mhr\EventSourcePhp\Domain\AggregateFactory());

$handler = new \Mhr\EventSourcePhp\CommandHandler\CreateAccountCommandHandler($accountRepository);
$handler2 = new \Mhr\EventSourcePhp\CommandHandler\AmountWasDepositedCommandHandler($accountRepository);

$commandBus = new \Symfony\Component\Messenger\MessageBus([
    new HandleMessageMiddleware(new HandlersLocator([
        CreateAccountCommand::class => [$handler],
        AmountWasDepositedCommand::class => [$handler2],
    ])),
]);

$id = '1234';
$commandBus->dispatch(new CreateAccountCommand($id));
$commandBus->dispatch(new \Mhr\EventSourcePhp\Command\AmountWasDepositedCommand($id, '100'));

