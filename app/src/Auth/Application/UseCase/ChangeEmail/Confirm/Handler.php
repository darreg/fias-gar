<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangeEmail\Confirm;

use App\Auth\Domain\Exception\ChangeEmailException;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

final class Handler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;

    public function __construct(
        UserRepositoryInterface $users,
        FlusherInterface $flusher
    ) {
        $this->userRepository = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->userRepository->findByNewEmailToken($command->getToken())) {
            throw new ChangeEmailException('Incorrect token.');
        }

        $user->changeEmailConfirm(
            $command->getToken(),
            new DateTimeImmutable()
        );

        $this->flusher->flush();
    }
}
