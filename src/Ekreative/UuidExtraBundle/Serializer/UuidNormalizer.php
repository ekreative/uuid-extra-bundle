<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Serializer;

use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        try {
            return Uuid::fromString($data);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException('Not a valid uuid string', 0, $e);
        }
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === UuidInterface::class || $type === Uuid::class || $type === LazyUuidFromString::class;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return (string) $object;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof UuidInterface;
    }
}
