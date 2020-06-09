<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\CommandHandler;

use Mhr\EventSourcePhp\Command\AccountWasCreated;
use Mhr\EventSourcePhp\Domain\Account\Account;
use Mhr\EventSourcePhp\Repository\AccountRepository;

class AccountHandler
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(AccountWasCreated $command)
    {
        $account = Account::create();
        $this->accountRepository->save($account);
    }
}
