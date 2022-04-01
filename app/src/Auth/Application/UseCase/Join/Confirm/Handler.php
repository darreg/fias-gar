<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\Join\Confirm;

use App\Auth\Domain\Exception\InvalidTokenException;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

final class Handler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function __invoke(Command $command): void
    {
        if (!$user = $this->userRepository->findByJoinConfirmToken($command->getToken())) {
            throw new InvalidTokenException('Token is invalid');
        }

        $user->joinConfirm($command->getToken(), new DateTimeImmutable());

        $this->flusher->flush();
    }
}
