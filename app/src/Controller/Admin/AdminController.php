<?php

namespace App\Controller\Admin;

use App\DTO\AdminDTO;
use App\DTO\AdminNewDTO;
use App\Form\Type\AdminType;
use App\Service\AdminService;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/admin")
 *
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class AdminController extends AbstractController
{
    private AdminService $adminService;

    public function __construct(
        AdminService $adminService
    ) {
        $this->adminService = $adminService;
    }

    /**
     * @Route("/new", name="admin-new", methods={"GET"})
     */
    public function new(): Response
    {
        $form = $this->adminService->createForm(AdminType::class, AdminNewDTO::class);

        return $this->render('admin/admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="admin-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->adminService->createForm(AdminType::class, AdminNewDTO::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $adminDto = AdminNewDTO::fromArray($data);
            $id = $this->adminService->add($adminDto);
            return $this->redirectToRoute('admin-edit', ['id' => $id]);
        }

        return $this->render('admin/admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin-edit", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function edit(int $id): Response
    {
        $admin = $this->adminService->getOne($id);
        if ($admin === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->adminService->createForm(
            AdminType::class,
            AdminDTO::class,
            $admin,
            ['password_require' => false]
        );

        return $this->render('admin/admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin-edit-submit", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function editSubmit(Request $request, int $id): Response
    {
        if ($id === 1) {
            throw new EntityNotFoundException('Редактирование запрещено');
        }

        $admin = $this->adminService->getOne($id);
        if ($admin === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->adminService->createForm(
            AdminType::class,
            AdminDTO::class,
            $admin,
            ['password_require' => false]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var AdminDTO $adminDto */
            $adminDto = $form->getData();
            $this->adminService->update($admin, $adminDto);

            return $this->redirectToRoute(
                'admin-edit',
                [
                    'id' => $admin->getId()
                ]
            );
        }

        return $this->render('admin/admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
