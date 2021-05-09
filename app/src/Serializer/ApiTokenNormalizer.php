<?php

namespace App\Serializer;

use App\Entity\ApiToken;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiTokenNormalizer implements NormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(
        ObjectNormalizer $normalizer
    ) {
        $this->normalizer = $normalizer;
    }

    /**
     * @param ApiToken $object
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['user', 'expiresAt', 'createdAt', 'updatedAt'];

        $data = $this->normalizer->normalize($object, $format, $context);

        if (\is_array($data)) {
            $user = $object->getUser();
            if ($user !== null) {
                $data['user'] = $user->getId();
            }
            $data['expiresAt'] = $object->getExpiresAt()->format('Y-m-d H:i');
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof ApiToken;
    }
}
