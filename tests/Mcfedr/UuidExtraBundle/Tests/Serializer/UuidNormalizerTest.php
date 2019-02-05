<?php

declare(strict_types=1);

namespace Mcfedr\UuidExtraBundle\Tests\Serializer;

use Mcfedr\UuidExtraBundle\Serializer\UuidNormalizer;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class UuidNormalizerTest extends TestCase
{
    public function testSerialization()
    {
        $serializer = new Serializer([new UuidNormalizer()], [new JsonEncoder()]);
        $uuid = Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7');

        $data = $serializer->serialize($uuid, 'json');
        $this->assertEquals('"f13a5b20-9741-4b15-8120-138009d8e0c7"', $data);
    }

    public function testNormalization()
    {
        $serializer = new Serializer([new UuidNormalizer()], [new JsonEncoder()]);
        $obj = $serializer->deserialize('"f13a5b20-9741-4b15-8120-138009d8e0c7"', UuidInterface::class, 'json');

        $uuid = Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7');
        $this->assertEquals($uuid, $uuid);
    }
}
