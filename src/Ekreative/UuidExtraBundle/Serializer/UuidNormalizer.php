<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Serializer;

use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function gettype;
use function is_string;
use function sprintf;

class UuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** {@inheritDoc} */
    public function denormalize($data, $type, $format = null, array $context = []): mixed
    {
        if (! is_string($data)) {
            throw new InvalidArgumentException(sprintf(
                'Not a valid uuid string - "string" expected, "%s" given',
                gettype($data)
            ));
        }

        try {
            return Uuid::fromString($data);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException('Not a valid uuid string', 0, $e);
        }
    }

    /** {@inheritDoc} */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return $type === UuidInterface::class || $type === Uuid::class || $type === LazyUuidFromString::class;
    }

    /** {@inheritDoc} */
    public function normalize($object, $format = null, array $context = []): string
    {
        return (string) $object;
    }

    /** {@inheritDoc} */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof UuidInterface;
    }
}
