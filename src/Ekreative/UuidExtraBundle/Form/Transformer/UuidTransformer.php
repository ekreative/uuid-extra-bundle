<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form\Transformer;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UuidTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof UuidInterface) {
            throw new TransformationFailedException('Expected a UuidInterface.');
        }

        return (string) $value;
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return;
        }

        if (!\is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        try {
            return Uuid::fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw new TransformationFailedException('Not a valid uuid string', 0, $e);
        }
    }
}
