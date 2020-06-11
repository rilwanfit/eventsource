<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\CommandHandler;

use Mhr\EventSourcePhp\Command\AmountWasDepositedCommand;
use Mhr\EventSourcePhp\Repository\AccountRepository;

class AmountWasDepositedCommandHandler
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function __invoke(AmountWasDepositedCommand $command)
    {
        $account = $this->accountRepository->findById($command->id);
        $account->deposit();

        $this->accountRepository->save($account);
    }
}
