<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form\Transformer;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use function is_string;

class UuidTransformer implements DataTransformerInterface
{
    /** {@inheritDoc} */
    public function transform($value): string
    {
        if ($value === null) {
            return '';
        }

        if (! $value instanceof UuidInterface) {
            throw new TransformationFailedException('Expected a UuidInterface.');
        }

        return (string) $value;
    }

    /** {@inheritDoc} */
    public function reverseTransform($value): ?UuidInterface
    {
        if (empty($value)) {
            return null;
        }

        if (! is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        try {
            return Uuid::fromString($value);
        } catch (InvalidArgumentException $e) {
            throw new TransformationFailedException('Not a valid uuid string', 0, $e);
        }
    }
}
