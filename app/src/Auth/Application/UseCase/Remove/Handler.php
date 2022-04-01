<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\Remove;

use App\Auth\Domain\User\Entity\Id;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Domain\Persistence\FlusherInterface;

final class Handler
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
        $user = $this->userRepository->findOrFail(new Id($command->getId()));

        $user->remove();

        $this->userRepository->remove($user);

        $this->flusher->flush();
    }
}
