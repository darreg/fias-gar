<?php

namespace App\Controller\Api\v1;

use App\DTO\ExtAddrobjDTO;
use App\Entity\ExtAddrobj;
use App\Service\ExtAddrobjService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/extaddrobj") */
class ExtAddrobjController
{
    public const PER_PAGE_DEFAULT = 20;

    private ExtAddrobjService $extAddrobjService;

    public function __construct(ExtAddrobjService $extAddrobjService)
    {
        $this->extAddrobjService = $extAddrobjService;
    }

    /**
     * @Route("/{objectid}", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function getOne(int $objectid): JsonResponse
    {
        $extAddrobj = $this->extAddrobjService->getOne($objectid);
        if ($extAddrobj === null) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(
            ['result' => $extAddrobj],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/all", methods={"GET"})
     */
    public function getAll(Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('perPage', self::PER_PAGE_DEFAULT);
        $page = $request->query->getInt('page');
        $extAddrobjs = $this->extAddrobjService->getAll($perPage, $page);
        $code = count($extAddrobjs) === 0 ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(
            ['result' => array_map(static fn(ExtAddrobj $extAddrobj) => $extAddrobj, $extAddrobjs)],
            $code
        );
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $extAddrobjDto = ExtAddrobjDTO::fromArray($request->request->all());

        $result = $this->extAddrobjService->add($extAddrobjDto);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("", methods={"PUT"})
     */
    public function update(Request $request): JsonResponse
    {
        $objectid = $request->query->getInt('objectid');

        $extAddrobjDto = ExtAddrobjDTO::fromArray($request->query->all());

        $result = $this->extAddrobjService->updateById(
            $objectid,
            $extAddrobjDto
        );

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Route("/{objectid}", methods={"DELETE"}, requirements={"objectid":"\d+"})
     */
    public function delete(int $objectid): JsonResponse
    {
        $result = $this->extAddrobjService->deleteById($objectid);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
