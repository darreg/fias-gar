<?php

namespace App\DataLoad\Presentation\Controller;

use App\DataLoad\Application\UseCase\Download\Command as DownloadCommand;
use App\DataLoad\Application\UseCase\FindFile\Query as FindFileQuery;
use App\DataLoad\Application\UseCase\FindFile\Response as FindFileResponse;
use App\DataLoad\Application\UseCase\ParseTag\Command;
use App\DataLoad\Application\UseCase\SplitFile\Command as SplitFileCommand;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    private QueryBus $queryBus;
    private CommandBus $commandBus;
//    private DoctrineFlusher $doctrineFlusher;
//    private EntityManagerInterface $entityManager;
//    private FiasTableSaver $dataSaver;
//    private FiasTableParameter $fiasTableParameters;
//    private FiasTableFactory $fiasTableFactory;

    public function __construct(
//        FiasTableParameter     $fiasTableParameters,
//        FiasTableFactory       $fiasTableFactory,
//        FiasTableSaver         $dataSaver,
//        EntityManagerInterface $entityManager,
//        DoctrineFlusher        $doctrineFlusher,

        QueryBus $queryBus,
        CommandBus $commandBus
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
//        $this->doctrineFlusher = $doctrineFlusher;
//        $this->entityManager = $entityManager;
//        $this->dataSaver = $dataSaver;
//        $this->fiasTableParameters = $fiasTableParameters;
//        $this->fiasTableFactory = $fiasTableFactory;
    }

    /**
     * @Route("/", name="main")
     */
    public function index(Connection $connection): Response // \App\DataLoad\Application\UseCase\ParseTag\Handler $handler
    {
        $command = new DownloadCommand('20220111');

        $this->commandBus->dispatch($command);

//        $query = new FindFileQuery('addr_obj');
//        /** @var FindFileResponse $response */
//        $response = $this->queryBus->ask($query);
//        foreach ($response->getAll() as $file) {
//            $command = new SplitFileCommand($file->getPath(), $file->getToken(), $file->getTagName());
//            $this->commandBus->dispatch($command);
//        }

//        $fileName = 'AS_HOUSE_TYPES_20210121_d2e6d657-245d-4eaf-b587-fc697580983a.XML';
//        $file = $this->fileFactory->create($fileName);
//
//        $command = new SplitFileCommand($file->getPath(), $file->getToken(), $file->getTagName());
//
//        $this->commandBus->dispatch($command);

//        $parseCommand = new Command(
//            'house_types',
//            '<HOUSETYPE ID="1" NAME="Владение" SHORTNAME="влд." DESC="Владение" STARTDATE="1900-01-01" ENDDATE="2015-11-05" UPDATEDATE="1900-01-01" ISACTIVE="false" />'
//        );

//        $handler($parseCommand);

//        $this->commandBus->dispatch($parseCommand);

//        $this->commandBus->dispatch(new SaveCommand('house_types', [
//            'id' => '1',
//            'name' => 'Владение',
//            'shortname' => 'влд.',
//            'desc' => 'Владение',
//            'startdate' => '1900-01-01',
//            'enddate' => '2015-11-05',
//            'updatedate' => '1900-01-01',
//            'isactive' => 'false'
//        ]));

//        $this->dataSaver->getTableColumns('fias_gar_addrobjtypes');

//        $driver = $connection->getDriver();

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

        return new Response('<html><body>Welcome!</body></html>');
    }
}
