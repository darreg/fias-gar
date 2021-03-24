<?php

namespace App\Controller\Admin;

use App\Entity\ExtHouse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/new")
     */
    public function new(Request $request): Response
    {

        $task = new ExtHouse();
        $task->setObjectid(37745114);

        $form = $this->createFormBuilder($task)
            ->add('precision', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('latitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('longitude', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('zoom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create ExtHouse',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');
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
