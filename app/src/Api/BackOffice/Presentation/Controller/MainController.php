<?php

namespace App\Api\BackOffice\Presentation\Controller;

use App\Auth\Application\UseCase\UserCreate\Command;
use App\Auth\Domain\User\Entity\Name;
use App\Auth\Infrastructure\Repository\UserFetcher;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
final class MainController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private UserFetcher $userFetcher;

    public function __construct(
        UserFetcher $userFetcher,
        CommandBusInterface $commandBus
    ) {
        $this->commandBus = $commandBus;
        $this->userFetcher = $userFetcher;
    }

    /**
     * @Route("/", name="admin_main")
     */
    public function index(): Response
    {
        $r = $this->userFetcher->findForAuthByEmail('ivan@ivanov.ru');
//        print_r($r);

//        $command = new Command('ivan@ivanov.ru', 'Иван', 'Иванов');
//        $this->commandBus->dispatch($command);

        return new Response('<html><body>Admin!</body></html>');
    }
}
