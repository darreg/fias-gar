<?php

namespace App\Controller;

use App\Repository\AddrobjAdmRepository;
use App\Repository\HouseAdmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private AddrobjAdmRepository $addrobjAdmRepository;
    private HouseAdmRepository $houseAdmRepository;

    public function __construct(
        AddrobjAdmRepository $addrobjAdmRepository,
        HouseAdmRepository $houseAdmRepository
    ) {
        $this->addrobjAdmRepository = $addrobjAdmRepository;
        $this->houseAdmRepository = $houseAdmRepository;
    }

    /**
     * @Route("/")
     */
    public function index(): Response
    {
//        $houses = $this->houseAdmRepository->findBy(['parentobjid' => 1305645]);
//        dump($houses);


//        $addrobjAdm = $this->addrobjAdmRepository->find(1316173);
//        //$addrobjAdm = $this->addrobjAdmRepository->find(1055230);
//        $addrobjAdm->getExtAddrobj()->getAlias();


//        $addrobjAdms = $this->addrobjAdmRepository->findAddrobjectTree(13161732222);

        //$count = $this->addrobjAdmRepository->count([]);
//        $count = $this->houseAdmRepository->count([]);

//        dump($count);

//        dump($addrobjAdms);

        return new Response("<html><body>!!!</body></html>");
    }
}
