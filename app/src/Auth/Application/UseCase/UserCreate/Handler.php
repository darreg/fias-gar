<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\UserCreate;

use App\Auth\Domain\Shared\ReadModel\AuthModel;
use App\Auth\Domain\Shared\Service\PasswordGeneratorInterface;
use App\Auth\Domain\Shared\Service\PasswordHasherInterface;
use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Entity\Id;
use App\Auth\Domain\User\Entity\Name;
use App\Auth\Domain\User\Entity\Status;
use App\Auth\Domain\User\Entity\User;
use App\Auth\Domain\User\Repository\UserFetcherInterface;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Persistence\FlusherInterface;
use RuntimeException;

class Handler implements CommandHandlerInterface
{
    private UserFetcherInterface $userFetcher;
    private UserRepositoryInterface $userRepository;
    private FlusherInterface $flusher;
    private PasswordHasherInterface $passwordHasher;
    private PasswordGeneratorInterface $passwordGenerator;

    public function __construct(
        UserFetcherInterface $userFetcher,
        UserRepositoryInterface $userRepository,
        FlusherInterface $flusher,
        PasswordHasherInterface $passwordHasher,
        PasswordGeneratorInterface $passwordGenerator
    ) {
        $this->userFetcher = $userFetcher;
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->passwordHasher = $passwordHasher;
        $this->passwordGenerator = $passwordGenerator;
    }

    public function __invoke(Command $command)
    {
        $email = new Email($command->getEmail());

        if ($this->userFetcher->hasByEmail($email)) {
            throw new RuntimeException('User with this email already exists.');
        }

        $id = Id::next();
        $status = Status::active();
        $name = new Name(
            $command->getFirstName(),
            $command->getLastName()
        );

        $passwordHash = $this->passwordHasher->hashPassword(
            new AuthModel($id->getValue(), $email->getValue(), '', $status->getName(), []),
            $this->passwordGenerator->generate()
        );

        $this->userRepository->persist(User::create($id, $name, $email, $status, [], $passwordHash));

        $this->flusher->flush();
    }
}
