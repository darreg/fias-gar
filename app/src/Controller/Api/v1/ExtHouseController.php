<?php

namespace App\Controller\Api\v1;

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
            ['result' => $extHouse->toArray()],
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
            ['result' => array_map(static fn(ExtHouse $extHouse) => $extHouse->toArray(), $extHouses)],
            $code
        );
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        /** @var int $objectid */
        $objectid = $request->request->get('objectid');
        /** @var string|null $objectguid */
        $objectguid = $request->request->get('objectguid');
        /** @var int|null $precision */
        $precision = $request->request->get('precision');
        /** @var float|null $latitude */
        $latitude = $request->request->get('latitude');
        /** @var float|null $longitude */
        $longitude = $request->request->get('longitude');
        /** @var int|null $zoom */
        $zoom = $request->request->get('zoom');

        $result = $this->extHouseManager->add(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom
        );

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("", methods={"PATCH"})
     */
    public function updateFields(Request $request): JsonResponse
    {
        $data = $request->query->all();

        /**
         * @psalm-var array{
         *     objectid: int,
         *     objectguid?: string,
         *     precision?: int,
         *     latitude?: float,
         *     longitude?: float,
         *     zoom?: int
         * } $data
         */
        $result = $this->extHouseManager->updateFieldsById(
            (int) $data['objectid'],
            $data
        );

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Route("", methods={"PUT"})
     */
    public function update(Request $request): JsonResponse
    {
        /** @var int $objectid */
        $objectid = $request->query->get('objectid');
        /** @var string|null $objectguid */
        $objectguid = $request->query->get('objectguid');
        /** @var int|null $precision */
        $precision = $request->query->get('precision');
        /** @var float|null $latitude */
        $latitude = $request->query->get('latitude');
        /** @var float|null $longitude */
        $longitude = $request->query->get('longitude');
        /** @var int|null $zoom */
        $zoom = $request->query->get('zoom');

        $result = $this->extHouseManager->updateById(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom
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
