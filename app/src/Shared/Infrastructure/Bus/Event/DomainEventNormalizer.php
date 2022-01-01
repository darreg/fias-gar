<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class DomainEventNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof DomainEvent;
    }
}
