<?php

namespace App\DataLoad\Presentation\Controller;

use App\DataLoad\Application\UseCase\CheckNewVersion\Command;
use App\DataLoad\Application\UseCase\FirstQuery\Query;
use App\DataLoad\Infrastructure\FiasTable\FiasTableFactory;
use App\DataLoad\Infrastructure\FiasTable\FiasTableParameters;
use App\DataLoad\Infrastructure\FiasTable\FiasTableSaver;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Persistence\DoctrineFlusher;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private DoctrineFlusher $doctrineFlusher;
    private EntityManagerInterface $entityManager;
    private FiasTableSaver $dataSaver;
    private FiasTableParameters $fiasTableParameters;
    private FiasTableFactory $fiasTableFactory;

    public function __construct(
        FiasTableParameters    $fiasTableParameters,
        FiasTableFactory    $fiasTableFactory,
        FiasTableSaver         $dataSaver,
        EntityManagerInterface $entityManager,
        DoctrineFlusher        $doctrineFlusher,
        QueryBus               $queryBus,
        CommandBus             $commandBus
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->doctrineFlusher = $doctrineFlusher;
        $this->entityManager = $entityManager;
        $this->dataSaver = $dataSaver;
        $this->fiasTableParameters = $fiasTableParameters;
        $this->fiasTableFactory = $fiasTableFactory;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(Connection $connection): Response
    {

//        $this->dataSaver->getTableColumns('fias_gar_addrobjtypes');

        $driver = $connection->getDriver();

//        $pdo = $connection->getDriver()->getWrappedConnection();
//        dump($pdo);
//
//        $query = $pdo->query('SELECT * FROM fias_gar_addrobjtypes LIMIT 1');
//        $query->

//        $data = $pdo->
//            query('SELECT * FROM fias_gar_addrobjtypes LIMIT 1')->
//            fetchAll(PDO::FETCH_CLASS, FiasGarAddrobjtypes::class);
//
//        dump($data);

//        $entityRepository = $this->entityManager->getRepository(ExtHouse::class);
//        $extHouseRepository = new ExtHouseRepository($this->entityManager, $entityRepository);

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
