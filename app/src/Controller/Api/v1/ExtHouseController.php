<?php

namespace App\Controller\Api\v1;

use App\DTO\ExtHouseDTO;
use App\Entity\ExtHouse;
use App\Manager\ExtHouseManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/exthouse") */
class ExtHouseController
{
    public const PER_PAGE_DEFAULT = 20;

    private ExtHouseManager $extHouseManager;

    public function __construct(ExtHouseManager $extHouseManager)
    {
        $this->extHouseManager = $extHouseManager;
    }

    /**
     * @Route("/{objectid}", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function getOne(int $objectid): JsonResponse
    {
        $extHouse = $this->extHouseManager->getOne($objectid);
        if ($extHouse === null) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(
            ['result' => $extHouse],
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
        $extHouses = $this->extHouseManager->getAll($perPage, $page);
        $code = count($extHouses) === 0 ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(
            ['result' => array_map(static fn(ExtHouse $extHouse) => $extHouse, $extHouses)],
            $code
        );
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $extHouseDTO = ExtHouseDTO::fromArray($request->request->all());

        $result = $this->extHouseManager->add($extHouseDTO);

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
        /** @var int $objectid */
        $objectid = $request->query->get('objectid');

        $extHouseDto = ExtHouseDTO::fromArray($request->query->all());

        $result = $this->extHouseManager->updateById(
            $objectid,
            $extHouseDto
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
        $result = $this->extHouseManager->deleteById($objectid);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
