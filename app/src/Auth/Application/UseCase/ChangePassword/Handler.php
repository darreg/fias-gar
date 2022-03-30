<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangePassword;

use App\Auth\Domain\User\Entity\Id;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\Service\PasswordHasherInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;

final class Handler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private FlusherInterface $flusher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        FlusherInterface $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findOrFail(new Id($command->getId()));

        $user->changePassword(
            $command->getCurrent(),
            $command->getNew(),
            $this->passwordHasher
        );

        $this->flusher->flush();
    }
}
