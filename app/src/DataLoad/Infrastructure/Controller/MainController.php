<?php

namespace App\DataLoad\Infrastructure\Controller;

use App\DataLoad\Application\UseCase\CheckNewVersion\Command;
use App\Shared\Infrastructure\Bus\Command\InMemory\CommandBus;
use App\Shared\Infrastructure\Bus\Query\InMemory\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $command = new Command('id', 'XXX!');
        $this->commandBus->dispatch($command);

        return new Response("<html><body>Welcome!</body></html>");
    }
}
