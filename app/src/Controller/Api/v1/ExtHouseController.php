<?php

namespace App\Controller\Api\v1;

use App\DTO\ExtHouseDTO;
use App\Entity\ExtHouse;
use App\Manager\ExtHouseManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @Route("/api/v1/exthouse") */
class ExtHouseController
{
    use GetValidatorErrors;

    public const PER_PAGE_DEFAULT = 20;

    private ExtHouseManager $extHouseManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ExtHouseManager $extHouseManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->extHouseManager = $extHouseManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
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

        $json = $this->serializer->serialize(['result' => $extHouse], 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/all", methods={"GET"})
     */
    public function getAll(Request $request): JsonResponse
    {
        $perPage = $request->query->getInt('perPage', self::PER_PAGE_DEFAULT);
        $page = $request->query->getInt('page');
        $extHouses = $this->extHouseManager->getAll($perPage, $page);
        if (count($extHouses) === 0) {
            return new JsonResponse(['result' => []], Response::HTTP_NO_CONTENT);
        }

        $json = $this->serializer->serialize(
            [
                'result' => array_map(static fn(ExtHouse $extHouse) => $extHouse, $extHouses)
            ],
            'json'
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $extHouseDto = ExtHouseDTO::fromArray($request->request->all());

        $violations = $this->validator->validate($extHouseDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->extHouseManager->add($extHouseDto);

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

        $violations = $this->validator->validate($extHouseDto);
        if (count($violations) > 0) {
            return new JsonResponse(
                [
                    'result' => [],
                    'errors' => $this->getValidatorErrors($violations)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

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
