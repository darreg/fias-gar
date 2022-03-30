<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\JoinByEmail\Request;

use App\Auth\Domain\Exception\UserExistsException;
use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Entity\Id;
use App\Auth\Domain\User\Entity\Name;
use App\Auth\Domain\User\Entity\User;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\Service\PasswordHasherInterface;
use App\Auth\Domain\User\Service\TokenizerInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use DateTimeImmutable;

final class Handler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private PasswordHasherInterface $passwordHasher;
    private TokenizerInterface $tokenizer;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher,
        PasswordHasherInterface $passwordHasher,
        TokenizerInterface $tokenizer
    ) {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->passwordHasher = $passwordHasher;
        $this->tokenizer = $tokenizer;
    }

    public function __invoke(Command $command)
    {
        $email = new Email($command->getEmail());

        if ($this->userRepository->hasByEmail($email)) {
            throw new UserExistsException('User with this email already exists.');
        }

        $this->userRepository->persist(
            User::joinByEmail(
                Id::next(),
                new Name(
                    $command->getFirstName(),
                    $command->getLastName()
                ),
                $email,
                $this->passwordHasher->hashPassword($command->getPassword()),
                $this->tokenizer->generate(new DateTimeImmutable())
            )
        );

        $this->flusher->flush();

        //TODO send token to mail
    }
}
