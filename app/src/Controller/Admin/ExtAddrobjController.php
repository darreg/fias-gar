<?php

namespace App\Controller\Admin;

use App\DTO\ExtAddrobjDTO;
use App\Entity\ExtAddrobj;
use App\Entity\ExtAddrobjPoint;
use App\Form\Type\ExtAddrobjType;
use App\Repository\ExtAddrobjRepository;
use App\Service\ExtAddrobjService;
use Doctrine\Common\Collections\ArrayCollection;
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
    )
    {
        $this->extAddrobjService = $extAddrobjService;
    }
    
    /**
     * @Route("/new", name="extaddrobj-new", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(ExtAddrobjType::class);
        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="extaddrobj-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->createForm(ExtAddrobjType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $extAddrobjDto = ExtAddrobjDTO::fromArray($data);
            $objectid = $this->extAddrobjService->add($extAddrobjDto);
            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $objectid]);
        }

        return $this->render('admin/extaddrobj/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{objectid}", name="extaddrobj-edit", requirements={"objectid":"\d+"})
     */
    public function edit(Request $request, int $objectid): Response
    {
        $extAddrobj = $this->extAddrobjService->getOne($objectid);
        if ($extAddrobj === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

//        $originalSynonym = new ArrayCollection();
//        foreach ($extAddrobj->getSynonym() as $synonym) {
//            $originalSynonym->add($synonym);
//        }
//
//        $originalPolygon = new ArrayCollection();
//        foreach ($extAddrobj->getPolygon() as $point) {
//            $originalPolygon->add($point);
//        }

        $form = $this->createForm(ExtAddrobjType::class, ExtAddrobjDTO::fromEntity($extAddrobj));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->extAddrobjService->update($extAddrobj, $form->getData());

//            foreach ($originalSynonym as $synonym) {
//                if ($extAddrobj->getSynonym()->contains($synonym) === false) {
//                    $entityManager->persist($synonym);
//                    $entityManager->remove($synonym);
//                }
//            }
//
//            foreach ($originalPolygon as $point) {
//                if ($extAddrobj->getPolygon()->contains($point) === false) {
//                    $entityManager->persist($point);
//                    $entityManager->remove($point);
//                }
//            }
//
//            $entityManager->persist($extAddrobj);
//            $entityManager->flush();

            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $extAddrobj->getObjectid()]);
        }

        return $this->render('admin/extaddrobj/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
