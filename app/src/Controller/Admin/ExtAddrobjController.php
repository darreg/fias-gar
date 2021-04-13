<?php

namespace App\Controller\Admin;

use App\DTO\ExtAddrobjDTO;
use App\Form\Type\ExtAddrobjType;
use App\Service\ExtAddrobjService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/extaddrobj")
 */
class ExtAddrobjController extends AbstractController
{
    private ExtAddrobjService $extAddrobjService;

    public function __construct(
        ExtAddrobjService $extAddrobjService
    ) {
        $this->extAddrobjService = $extAddrobjService;
    }

    /**
     * @Route("/new", name="extaddrobj-new", methods={"GET"})
     */
    public function new(): Response
    {
        $form = $this->extAddrobjService->createForm(ExtAddrobjType::class);

        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="extaddrobj-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->extAddrobjService->createForm(ExtAddrobjType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $extAddrobjDto = ExtAddrobjDTO::fromArray($data);
            $objectid = $this->extAddrobjService->add($extAddrobjDto);
            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $objectid]);
        }

        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="extaddrobj-edit", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function edit(int $objectid): Response
    {
        $extAddrobj = $this->extAddrobjService->getOne($objectid);
        if ($extAddrobj === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->extAddrobjService->createForm(ExtAddrobjType::class, $extAddrobj);

        return $this->render('admin/extaddrobj/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="extaddrobj-edit-submit", methods={"POST"}, requirements={"objectid":"\d+"})
     */
    public function editSubmit(Request $request, int $objectid): Response
    {
        $extAddrobj = $this->extAddrobjService->getOne($objectid);
        if ($extAddrobj === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->extAddrobjService->createForm(ExtAddrobjType::class, $extAddrobj);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var ExtAddrobjDTO $extAddrobjDto */
            $extAddrobjDto = $form->getData();
            $this->extAddrobjService->update($extAddrobj, $extAddrobjDto);

            return $this->redirectToRoute(
                'extaddrobj-edit',
                [
                    'objectid' => $extAddrobj->getObjectid()
                ]
            );
        }

        return $this->render('admin/extaddrobj/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
