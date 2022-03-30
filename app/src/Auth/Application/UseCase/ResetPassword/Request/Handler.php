<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ResetPassword\Request;

use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\Service\TokenizerInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

final class Handler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private TokenizerInterface $tokenizer;
    private FlusherInterface $flusher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenizerInterface $tokenizer,
        FlusherInterface $flusher,
    ) {
        $this->userRepository = $userRepository;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->getEmail());

        $user = $this->userRepository->findByEmailOrFail($email);

        $date = new DateTimeImmutable();
        $token = $this->tokenizer->generate($date);

        $user->resetPasswordRequest($token, $date);

        $this->flusher->flush();

        //TODO send token to mail
    }
}
