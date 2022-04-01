<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\ChangeEmail\Request;

use App\Auth\Domain\Exception\ChangeEmailException;
use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Entity\Id;
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
        FlusherInterface $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findOrFail(new Id($command->getId()));

        $email = new Email($command->getEmail());

        if ($this->userRepository->hasByEmail($email)) {
            throw new ChangeEmailException('Email is already in use.');
        }

        $date = new DateTimeImmutable();
        $token = $this->tokenizer->generate($date);

        $user->changeEmailRequest($token, $date, $email);

        $this->flusher->flush();

        //TODO send token to email
    }
}
