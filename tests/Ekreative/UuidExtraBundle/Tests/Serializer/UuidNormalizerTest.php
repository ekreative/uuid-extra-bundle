<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Tests\Serializer;

use Ekreative\UuidExtraBundle\Serializer\UuidNormalizer;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Serializer;

final class UuidNormalizerTest extends TestCase
{
    public function testSerialization(): void
    {
        $serializer = new Serializer([new UuidNormalizer()], [new JsonEncoder()]);
        $uuid       = Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7');

        $data = $serializer->serialize($uuid, 'json');
        $this->assertEquals('"f13a5b20-9741-4b15-8120-138009d8e0c7"', $data);
    }

    public function testNormalization(): void
    {
        $this->assertEquals(
            Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7'),
            (new Serializer([new UuidNormalizer()], [new JsonEncoder()]))
                ->deserialize('"f13a5b20-9741-4b15-8120-138009d8e0c7"', UuidInterface::class, 'json')
        );
    }

    public function testNormalizationRejectsInvalidUuidString(): void
    {
        $serializer = new Serializer([new UuidNormalizer()], [new JsonEncoder()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid uuid string');
        $serializer->deserialize('"not-valid"', UuidInterface::class, 'json');
    }

    public function testNormalizationRejectsNonStringUuid(): void
    {
        $serializer = new Serializer([new UuidNormalizer()], [new JsonEncoder()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid uuid string - "string" expected, "array" given');
        $serializer->deserialize('{"an": "object"}', UuidInterface::class, 'json');
    }
}
