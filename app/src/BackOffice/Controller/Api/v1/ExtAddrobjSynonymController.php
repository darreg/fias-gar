<?php

namespace App\BackOffice\Controller\Api\v1;

use App\BackOffice\DTO\ExtAddrobjSynonymDTO;
use App\BackOffice\Entity\ExtAddrobj;
use App\BackOffice\Entity\ExtAddrobjSynonym;
use App\BackOffice\Service\ExtAddrobjService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function App\Controller2\Api\v1\count;

/** @Route("/api/v1/extaddrobj/synonym") */
class ExtAddrobjSynonymController
{
    use GetValidatorErrors;

    private ExtAddrobjService $extAddrobjService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ExtAddrobjService $extAddrobjService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->extAddrobjService = $extAddrobjService;
        $this->serializer = $serializer;
        $this->validator = $validator;
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

        $json = $this->serializer->serialize(
            [
                'result' => $extAddrobjSynonym
            ],
            'json',
            [
                AbstractNormalizer::CALLBACKS => [
                    'extAddrobj' => static fn (ExtAddrobj $extAddrobj) => $extAddrobj->getObjectid()
                ],
            ]
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/all/{objectid}", methods={"GET"}, requirements={"objectid":"\d+"})
     */
    public function getAll(int $objectid): JsonResponse
    {
        $extAddrobjSynonyms = $this->extAddrobjService->getSynonymAll($objectid);
        if (count($extAddrobjSynonyms) === 0) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        $json = $this->serializer->serialize(
            [
                'result' => array_map(
                    static fn(ExtAddrobjSynonym $extAddrobjSynonym) => $extAddrobjSynonym,
                    $extAddrobjSynonyms
                )
            ],
            'json',
            [
                AbstractNormalizer::CALLBACKS => [
                    'extAddrobj' => static fn (ExtAddrobj $extAddrobj) => $extAddrobj->getObjectid()
                ],
            ]
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $extAddrobjSynonymDto = ExtAddrobjSynonymDTO::fromArray($request->request->all());

        $violations = $this->validator->validate($extAddrobjSynonymDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->extAddrobjService->addSynonym(
            $extAddrobjSynonymDto
        );

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
        /** @var int $id */
        $id = $request->query->get('id');

        $extAddrobjSynonymDto = ExtAddrobjSynonymDTO::fromArray($request->query->all());

        $violations = $this->validator->validate($extAddrobjSynonymDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->extAddrobjService->updateSynonymById(
            $id,
            $extAddrobjSynonymDto
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
