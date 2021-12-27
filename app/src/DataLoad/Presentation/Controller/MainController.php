<?php

namespace App\DataLoad\Presentation\Controller;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Api\Shared\Domain\Entity\ExtAddrobj\LatLon;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Id as PointId;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\LatLon as PointLatLon;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Point;
use App\Api\Shared\Infrastructure\Repository\ExtAddrobjRepository;
use App\DataLoad\Application\UseCase\OtherCommand\Command as OtherCommand;
use App\DataLoad\Application\UseCase\CheckNewVersion\Command;
use App\DataLoad\Application\UseCase\Parse\Command as ParseCommand;
use App\DataLoad\Application\UseCase\FirstQuery\Query;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Event\DomainEventNormalizer;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Persistence\DoctrineFlusher;
use App\Shared\Infrastructure\UuidGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private DoctrineFlusher $doctrineFlusher;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        DoctrineFlusher $doctrineFlusher,
        QueryBus $queryBus,
        CommandBus $commandBus
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->doctrineFlusher = $doctrineFlusher;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $extAddrobj = new ExtAddrobj(
            1316173,
            'cc1bf769-7f0b-44c9-968c-31a9cd8f7ab9',
            LatLon::fromString('11.222,22.333'),
            'test1',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        );

        $extAddrobj->addPoint(PointId::next(), 11.222, 22.333);
        $extAddrobj->addPoint(PointId::next(), 22.333, 33.444);

        $entityRepository = $this->entityManager->getRepository(ExtAddrobj::class);
        $extAddrobjRepository = new ExtAddrobjRepository($this->entityManager, $entityRepository);
        $extAddrobjRepository->add($extAddrobj);
        $this->doctrineFlusher->flush();

//
//        dump($point);

//        dump($uuidGenerator->generate());

//        $command = new ParseCommand('id1', 'Parse It!');
//        $this->commandBus->dispatch($command);


//        $command = new Command('id1', 'XXX!');
//        $this->commandBus->dispatch($command);
//
//        $command = new OtherCommand('id2', 'ZZZ!');
//        $this->commandBus->dispatch($command);

//        $query = new Query('id3', 'QQQ!');
//        $result = $this->queryBus->ask($query);
//        dump($result);

        return new Response("<html><body>Welcome!</body></html>");
    }
}
