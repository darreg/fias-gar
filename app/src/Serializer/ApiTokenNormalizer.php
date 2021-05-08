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

    public function normalize($apiToken, string $format = null, array $context = [])
    {
        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['user', 'expiresAt', 'createdAt', 'updatedAt'];
        
        $data = $this->normalizer->normalize($apiToken, $format, $context);
        
        if (\is_array($data)) {
            $user = $apiToken->getUser();
            if ($user !== null) {
                $data['user'] = $user->getId();
            }
            $expiresAt = $apiToken->getExpiresAt();
            if ($expiresAt !== null) {
                $data['expiresAt'] = $expiresAt->format('Y-m-d H:i');
            }
        }
        
        return $data;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof ApiToken;
    }    
}