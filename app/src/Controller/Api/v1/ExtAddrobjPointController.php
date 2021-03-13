<?php

namespace App\Controller\Api\v1;

use App\DTO\ExtAddrobjPointDTO;
use App\Entity\ExtAddrobjPoint;
use App\Service\ExtAddrobjService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/extaddrobj/point") */
class ExtAddrobjPointController
{
    private ExtAddrobjService $extAddrobjService;

    public function __construct(ExtAddrobjService $extAddrobjService)
    {
        $this->extAddrobjService = $extAddrobjService;
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function getOne(int $id): JsonResponse
    {
        $extAddrobjPoint = $this->extAddrobjService->getPointOne($id);
        if ($extAddrobjPoint === null) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(
            ['result' => $extAddrobjPoint->toArray()],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/all/{objectid}", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function getAll(int $objectid): JsonResponse
    {
        $extAddrobjPoints = $this->extAddrobjService->getPointAll($objectid);
        $code = count($extAddrobjPoints) === 0 ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(
            [
                'result' => array_map(
                    static fn(ExtAddrobjPoint $extAddrobjPoint) => $extAddrobjPoint->toArray(),
                    $extAddrobjPoints
                )
            ],
            $code
        );
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $extAddrobjPointDTO = ExtAddrobjPointDTO::fromArray($request->request->all());

        $result = $this->extAddrobjService->addPoint($extAddrobjPointDTO);

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
        $extAddrobjPointDTO = ExtAddrobjPointDTO::fromArray($request->query->all());

        $id = $request->query->getInt('id');

        $result = $this->extAddrobjService->updatePointById(
            $id,
            $extAddrobjPointDTO
        );

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(int $id): JsonResponse
    {
        $result = $this->extAddrobjService->deletePointById($id);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Route("/all/{objectid}", methods={"DELETE"}, requirements={"objectid":"\d+"})
     */
    public function deleteByObjectid(int $objectid): JsonResponse
    {
        $result = $this->extAddrobjService->deletePointByObjectId($objectid);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
