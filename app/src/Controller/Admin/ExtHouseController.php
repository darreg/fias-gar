<?php

namespace App\Controller\Admin;

use App\DTO\ExtHouseDTO;
use App\Form\Type\ExtHouseType;
use App\Service\ExtHouseService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/exthouse")
 */
class ExtHouseController extends AbstractController
{
    private ExtHouseService $extHouseService;

    public function __construct(
        ExtHouseService $extHouseService
    ) {
        $this->extHouseService = $extHouseService;
    }

    /**
     * @Route("/new", name="exthouse-new", methods={"GET"})
     */
    public function new(): Response
    {
        $form = $this->extHouseService->createForm(ExtHouseType::class);

        return $this->render('admin/exthouse/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="exthouse-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->extHouseService->createForm(ExtHouseType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $extHouseDto = ExtHouseDTO::fromArray($data);
            $objectid = $this->extHouseService->add($extHouseDto);
            return $this->redirectToRoute('exthouse-edit', ['objectid' => $objectid]);
        }

        return $this->render('admin/exthouse/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="exthouse-edit", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function edit(int $objectid): Response
    {
        $extHouse = $this->extHouseService->getOne($objectid);
        if ($extHouse === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->extHouseService->createForm(ExtHouseType::class, $extHouse);

        return $this->render('admin/exthouse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="exthouse-edit-submit", methods={"POST"}, requirements={"objectid":"\d+"})
     */
    public function editSubmit(Request $request, int $objectid): Response
    {
        $extHouse = $this->extHouseService->getOne($objectid);
        if ($extHouse === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->extHouseService->createForm(ExtHouseType::class, $extHouse);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var ExtHouseDTO $extHouseDto */
            $extHouseDto = $form->getData();
            $this->extHouseService->update($extHouse, $extHouseDto);

            return $this->redirectToRoute(
                'exthouse-edit',
                [
                    'objectid' => $extHouse->getObjectid()
                ]
            );
        }

        return $this->render('admin/exthouse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
