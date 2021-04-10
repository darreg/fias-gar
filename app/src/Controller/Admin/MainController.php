<?php

namespace App\Controller\Admin;

use App\Entity\ExtAddrobj;
use App\Form\Type\ExtAddrobjType;
use App\Repository\ExtAddrobjRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    private ExtAddrobjRepository $extAddrobjRepository;

    public function __construct(
        ExtAddrobjRepository $extAddrobjRepository
    )
    {
        $this->extAddrobjRepository = $extAddrobjRepository;
    }


    /**
     * @Route("/new")
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $extAddrobj = $this->extAddrobjRepository->find(1316173);
        dump($extAddrobj);

        $originalSynonym = new ArrayCollection();
        foreach ($extAddrobj->getSynonym() as $synonym) {
            $originalSynonym->add($synonym);
        }

        $form = $this->createForm(ExtAddrobjType::class, $extAddrobj);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ExtAddrobj $extAddrobj */
            $extAddrobj = $form->getData();

            foreach ($originalSynonym as $synonym) {
                if ($extAddrobj->getSynonym()->contains($synonym) === false) {
                    $synonym->getExtAddrobj()->removeElement($extAddrobj);
                    $synonym->setTask(null);

                    $entityManager->persist($synonym);
                    $entityManager->remove($synonym);
                }
            }

            $entityManager->persist($extAddrobj);
            $entityManager->flush();

//            return $this->redirectToRoute('task_success');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/edit/{objectid}", requirements={"objectid":"\d+"})
//     */
//    public function edit(int $objectid): Response
//    {
//        return $this->render('admin/new.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}
