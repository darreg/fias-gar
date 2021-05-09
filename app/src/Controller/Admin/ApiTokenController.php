<?php

namespace App\Controller\Admin;

use App\DTO\ApiTokenDTO;
use App\DTO\ApiTokenNewDTO;
use App\Entity\ApiToken;
use App\Form\Type\ApiTokenType;
use App\Service\ApiTokenService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/api_token")
 */
class ApiTokenController extends AbstractController
{
    private ApiTokenService $apiTokenService;

    public function __construct(
        ApiTokenService $apiTokenService
    ) {
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * @Route("/new", name="api-token-new", methods={"GET"})
     */
    public function new(): Response
    {
        $form = $this->apiTokenService->createForm(ApiTokenType::class, ApiTokenNewDTO::class);

        return $this->render('admin/api_token/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="api-token-new-submit", methods={"POST"})
     */
    public function newSubmit(Request $request): Response
    {
        $form = $this->apiTokenService->createForm(ApiTokenType::class, ApiTokenNewDTO::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = (array)$form->getData();
            $apiTokenDto = ApiTokenNewDTO::fromArray($data);
            $id = $this->apiTokenService->add($apiTokenDto);
            return $this->redirectToRoute('api-token-edit', ['id' => $id]);
        }

        return $this->render('admin/api_token/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="api-token-edit", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function edit(int $id): Response
    {
        $apiToken = $this->apiTokenService->getOne($id);
        if ($apiToken === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->apiTokenService->createForm(
            ApiTokenType::class,
            ApiTokenDTO::class,
            $apiToken
        );

        return $this->render('admin/api_token/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="api-token-edit-submit", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function editSubmit(Request $request, int $id): Response
    {
        $apiToken = $this->apiTokenService->getOne($id);
        if ($apiToken === null) {
            throw new EntityNotFoundException('Объект не найден');
        }

        $form = $this->apiTokenService->createForm(
            ApiTokenType::class,
            ApiTokenDTO::class,
            $apiToken
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var ApiTokenDTO $apiTokenDto */
            $apiTokenDto = $form->getData();
            $this->apiTokenService->update($apiToken, $apiTokenDto);

            return $this->redirectToRoute(
                'api-token-edit',
                [
                    'id' => $apiToken->getId()
                ]
            );
        }

        return $this->render('admin/api_token/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
