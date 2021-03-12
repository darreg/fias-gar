<?php

namespace App\Controller\Api\v1;

use App\Entity\ExtAddrobj;
use App\Entity\ExtAddrobjSynonym;
use App\Service\ExtAddrobjService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api/v1/extaddrobj/synonym") */
class ExtAddrobjSynonymController
{
    public const PER_PAGE_DEFAULT = 20;

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
        $extAddrobjSynonym = $this->extAddrobjService->getSynonymOne($id);
        if ($extAddrobjSynonym === null) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(
            ['result' => $extAddrobjSynonym->toArray()],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/all/{objectid}", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function getAll(int $objectid): JsonResponse
    {
        $extAddrobjSynonyms = $this->extAddrobjService->getSynonymAll($objectid);
        $code = count($extAddrobjSynonyms) === 0 ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(
            [
                'result' => array_map(
                    static fn(ExtAddrobjSynonym $extAddrobjSynonym) => $extAddrobjSynonym->toArray(),
                    $extAddrobjSynonyms
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
        /** @var int $objectid */
        $objectid = $request->request->get('objectid');
        /** @var string $name */
        $name = $request->request->get('name');

        $result = $this->extAddrobjService->addSynonym(
            $objectid,
            $name
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
         *     id: int,
         *     objectid?: int,
         *     name?: string,
         * } $data
         */
        $data = $request->query->all();

        $result = $this->extAddrobjService->updateSynonymFieldsById(
            $data['id'],
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
        /** @var int $id */
        $id = $request->query->get('id');
        /** @var int $objectid */
        $objectid = $request->query->get('objectid');
        /** @var string $name */
        $name = $request->query->get('name');

        $result = $this->extAddrobjService->updateSynonymById(
            $id,
            $objectid,
            $name
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
        $result = $this->extAddrobjService->deleteSynonymById($id);

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
        $result = $this->extAddrobjService->deleteSynonymByObjectId($objectid);

        return new JsonResponse(
            ['result' => $result],
            $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
