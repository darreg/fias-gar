<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ResetPassword\Confirm;

use App\Auth\Domain\Exception\ResetPasswordException;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\Service\PasswordHasherInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

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

    public function handle(Command $command): void
    {
        if (!$user = $this->userRepository->findByPasswordResetToken($command->getToken())) {
            throw new ResetPasswordException('Token is not found.');
        }

        $user->resetPasswordConfirm(
            $command->getToken(),
            new DateTimeImmutable(),
            $this->passwordHasher->hashPassword($command->getPassword())
        );

        $this->flusher->flush();
    }
}
