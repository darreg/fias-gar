<?php

namespace App\Controller\Api\v1;

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
            ['result' => $extAddrobj->toArray()],
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
            ['result' => array_map(static fn(ExtAddrobj $extAddrobj) => $extAddrobj->toArray(), $extAddrobjs)],
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
        /** @var string|null $alias */
        $alias = $request->request->get('alias');
        /** @var string|null $anglicism */
        $anglicism = $request->request->get('$anglicism');
        /** @var string|null $nominative */
        $nominative = $request->request->get('nominative');
        /** @var string|null $genitive */
        $genitive = $request->request->get('genitive');
        /** @var string|null $dative */
        $dative = $request->request->get('dative');
        /** @var string|null $accusative */
        $accusative = $request->request->get('accusative');
        /** @var string|null $ablative */
        $ablative = $request->request->get('ablative');
        /** @var string|null $prepositive */
        $prepositive = $request->request->get('prepositive');
        /** @var string|null $locative */
        $locative = $request->request->get('locative');

        $result = $this->extAddrobjService->add(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom,
            $alias,
            $anglicism,
            $nominative,
            $genitive,
            $dative,
            $accusative,
            $ablative,
            $prepositive,
            $locative
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
        /**
         * @psalm-var array{
         *     objectid: int,
         *     objectguid?: string,
         *     precision?: int,
         *     latitude?: float,
         *     longitude?: float,
         *     zoom?: int,
         *     alias?: string,
         *     anglicism?: string,
         *     nominative?: string,
         *     genitive?: string,
         *     dative?: string,
         *     accusative?: string,
         *     ablative?: string,
         *     prepositive?: string,
         *     locative?: string,
         * } $data
         */
        $data = $request->query->all();

        $result = $this->extAddrobjService->updateFieldsById(
            $data['objectid'],
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
        /** @var string|null $alias */
        $alias = $request->query->get('alias');
        /** @var string|null $anglicism */
        $anglicism = $request->query->get('$anglicism');
        /** @var string|null $nominative */
        $nominative = $request->query->get('nominative');
        /** @var string|null $genitive */
        $genitive = $request->query->get('genitive');
        /** @var string|null $dative */
        $dative = $request->query->get('dative');
        /** @var string|null $accusative */
        $accusative = $request->query->get('accusative');
        /** @var string|null $ablative */
        $ablative = $request->query->get('ablative');
        /** @var string|null $prepositive */
        $prepositive = $request->query->get('prepositive');
        /** @var string|null $locative */
        $locative = $request->query->get('locative');

        $result = $this->extAddrobjService->updateById(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom,
            $alias,
            $anglicism,
            $nominative,
            $genitive,
            $dative,
            $accusative,
            $ablative,
            $prepositive,
            $locative
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
