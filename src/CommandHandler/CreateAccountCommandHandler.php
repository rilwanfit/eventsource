<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\CommandHandler;

use Mhr\EventSourcePhp\Command\CreateAccountCommand;
use Mhr\EventSourcePhp\Domain\Account\Account;
use Mhr\EventSourcePhp\Repository\AccountRepository;

class CreateAccountCommandHandler
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(CreateAccountCommand $command)
    {
        $account = Account::create();
        $this->accountRepository->save($account);
    }
}
