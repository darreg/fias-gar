<?php

namespace App\Controller\Admin;

use App\DTO\UserDTO;
use App\DTO\UserNewDTO;
use App\Form\Type\UserType;
use App\Service\UserService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @Route("/new", name="user-new", methods={"GET"})
     */
    public function new(): Response
    {
        $form = $this->userService->createForm(UserType::class, UserNewDTO::class);

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="user-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->userService->createForm(UserType::class, UserNewDTO::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $userDto = UserNewDTO::fromArray($data);
            $id = $this->userService->add($userDto);
            return $this->redirectToRoute('user-edit', ['id' => $id]);
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user-edit", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function edit(int $id): Response
    {
        $user = $this->userService->getOne($id);
        if ($user === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->userService->createForm(
            UserType::class,
            UserDTO::class,
            $user,
            ['password_require' => false]
        );

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user-edit-submit", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function editSubmit(Request $request, int $id): Response
    {
        $user = $this->userService->getOne($id);
        if ($user === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->userService->createForm(
            UserType::class,
            UserDTO::class,
            $user,
            ['password_require' => false]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var UserDTO $userDto */
            $userDto = $form->getData();
            $this->userService->update($user, $userDto);

            return $this->redirectToRoute(
                'user-edit',
                [
                    'id' => $user->getId()
                ]
            );
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
