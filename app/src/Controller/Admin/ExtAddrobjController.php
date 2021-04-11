<?php

namespace App\Controller\Admin;

use App\DTO\ExtAddrobjDTO;
use App\Entity\ExtAddrobj;
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
    private ExtAddrobjRepository $extAddrobjRepository;
    private ExtAddrobjService $extAddrobjService;

    public function __construct(
        ExtAddrobjService $extAddrobjService,
        ExtAddrobjRepository $extAddrobjRepository
    )
    {
        $this->extAddrobjRepository = $extAddrobjRepository;
        $this->extAddrobjService = $extAddrobjService;
    }
    
    /**
     * @Route("/new", name="extaddrobj-new")
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(ExtAddrobjType::class);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $objectId = $this->extAddrobjService->add($form->getData());

            return $this->redirectToRoute('extaddrobj-edit', ['objectid' => $objectId]);
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
        $entityManager = $this->getDoctrine()->getManager();
        $extAddrobj = $this->extAddrobjRepository->find($objectid);
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
