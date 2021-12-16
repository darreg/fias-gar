<?php

namespace App\BackOffice\Controller\Api\v1;

use App\BackOffice\DTO\ExtAddrobjPointDTO;
use App\BackOffice\Entity\ExtAddrobj;
use App\BackOffice\Entity\ExtAddrobjPoint;
use App\BackOffice\Service\ExtAddrobjService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function App\Controller2\Api\v1\count;

/** @Route("/api/v1/extaddrobj/point") */
class ExtAddrobjPointController
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
        $extAddrobjPoint = $this->extAddrobjService->getPointOne($id);
        if ($extAddrobjPoint === null) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        $json = $this->serializer->serialize(
            [
                'result' => $extAddrobjPoint
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
        $extAddrobjPoints = $this->extAddrobjService->getPointAll($objectid);
        if (count($extAddrobjPoints) === 0) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        $json = $this->serializer->serialize(
            [
                'result' => array_map(
                    static fn(ExtAddrobjPoint $extAddrobjPoint) => $extAddrobjPoint,
                    $extAddrobjPoints
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
        $extAddrobjPointDto = ExtAddrobjPointDTO::fromArray($request->request->all());

        $violations = $this->validator->validate($extAddrobjPointDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->extAddrobjService->addPoint($extAddrobjPointDto);

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
        $extAddrobjPointDto = ExtAddrobjPointDTO::fromArray($request->query->all());

        $violations = $this->validator->validate($extAddrobjPointDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $id = $request->query->getInt('id');

        $result = $this->extAddrobjService->updatePointById(
            $id,
            $extAddrobjPointDto
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
