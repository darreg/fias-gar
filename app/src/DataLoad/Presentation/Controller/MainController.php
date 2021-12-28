<?php

namespace App\DataLoad\Presentation\Controller;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Api\Shared\Domain\Entity\ExtAddrobj\LatLon;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Id as PointId;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\LatLon as PointLatLon;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Point;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym\Synonym;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym\Id as SynonymId;
use App\Api\Shared\Domain\Entity\ExtHouse\ExtHouse;
use App\Api\Shared\Domain\Entity\ExtHouse\LatLon as ExtHouseLatLon;
use App\Api\Shared\Infrastructure\Repository\ExtAddrobjRepository;
use App\Api\Shared\Infrastructure\Repository\ExtHouseRepository;
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
        $entityRepository = $this->entityManager->getRepository(ExtHouse::class);
        $extHouseRepository = new ExtHouseRepository($this->entityManager, $entityRepository);

//        $extHouse = new ExtHouse(
//            37745114,
//            '271f7268-a51a-42fb-9d1b-dcdc817880fd',
//            ExtHouseLatLon::fromString('11.222,22.333')
//        );
//
//        $extHouseRepository->add($extHouse);
//        $this->doctrineFlusher->flush();



//
//        $entityRepository = $this->entityManager->getRepository(ExtAddrobj::class);
//        $extAddrobjRepository = new ExtAddrobjRepository($this->entityManager, $entityRepository);
//
//        $extAddrobj = new ExtAddrobj(
//            1316173,
//            'cc1bf769-7f0b-44c9-968c-31a9cd8f7ab9',
//            LatLon::fromString('11.222,22.333'),
//            'test1',
//            '',
//            '',
//            '',
//            '',
//            '',
//            '',
//            '',
//            '',
//        );
//
//        $extAddrobj->addPoint(PointId::next(), 11.222, 22.333);
//        $extAddrobj->addPoint(PointId::next(), 22.333, 33.444);
//        $extAddrobj->addPoint(PointId::next(), 22.333, 33.445);
//
//        $extAddrobj->addSynonym(SynonymId::next(), 'ййййй');
//        $extAddrobj->addSynonym(SynonymId::next(), 'ууууу');
//        $extAddrobj->addSynonym(SynonymId::next(), 'кккк');

//        $extAddrobjRepository->add($extAddrobj);


//        $extAddrobj = $extAddrobjRepository->get(1316173);
//
//        $synonyms = $extAddrobj->getSynonyms();
//        $synonym = reset($synonyms);
//
//        $extAddrobj->editSynonym($synonym->getId(), 'ZZZZZZZZZZZZZ!');
//
//        dump($synonyms);
//
//        $this->doctrineFlusher->flush();

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
