<?php

namespace App\DataLoad\Infrastructure\Controller;

use App\DataLoad\Application\UseCase\OtherCommand\Command as OtherCommand;
use App\DataLoad\Application\UseCase\CheckNewVersion\Command;
use App\DataLoad\Application\UseCase\FirstQuery\Query;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
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
        $command = new Command('id1', 'XXX!');
        $this->commandBus->dispatch($command);

        $command = new OtherCommand('id2', 'ZZZ!');
        $this->commandBus->dispatch($command);

        $query = new Query('id3', 'QQQ!');
        $result = $this->queryBus->ask($query);
        dump($result);

        return new Response("<html><body>Welcome!</body></html>");
    }
}
