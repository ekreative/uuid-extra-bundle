<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Tests\ArgumentResolver;

use Ekreative\UuidExtraBundle\ValueResolver\UuidValueResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UuidArgumentResolverTest extends TestCase
{
    /** @var UuidValueResolver */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new UuidValueResolver();
    }

    public function testSupports(): void
    {
        $config = $this->createConfiguration(Uuid::class);
        $this->assertTrue($this->converter->supports($config));

        $config = $this->createConfiguration(UuidInterface::class);
        $this->assertTrue($this->converter->supports($config));

        $config = $this->createConfiguration(self::class);
        $this->assertFalse($this->converter->supports($config));

        $config = $this->createConfiguration();
        $this->assertFalse($this->converter->supports($config));
    }

    public function testApply(): void
    {
        $request = new Request([], [], ['uuid' => 'f13a5b20-9741-4b15-8120-138009d8e0c7']);
        $config  = $this->createConfiguration(Uuid::class, 'uuid');

        $this->converter->resolve($request, $config);

        $this->assertEquals(
            Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7'),
            $request->attributes->get('uuid')
        );
    }

    public function testApplyInvalidValueTypeForUuidWillLeadToA404Exception(): void
    {
        $request = new Request([], [], ['uuid' => ['an', 'array', 'instead', 'of', 'a', 'string']]);
        $config  = $this->createConfiguration(Uuid::class, 'uuid');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Invalid uuid given - expected "string", "array" given');
        $this->converter->resolve($request, $config);
    }

    public function testApplyInvalidUuid404Exception(): void
    {
        $request = new Request([], [], ['uuid' => 'Invalid uuid Format']);
        $config  = $this->createConfiguration(Uuid::class, 'uuid');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Invalid uuid given');
        $this->converter->resolve($request, $config);
    }

    public function testApplyOptionalWithEmptyAttribute(): void
    {
        $request = new Request([], [], ['uuid' => null]);
        $config  = $this->createConfiguration(Uuid::class, 'uuid');
        $config->expects($this->once())
            ->method('isNullable')
            ->willReturn(true);

        $this->assertCount(0, $this->converter->resolve($request, $config));
        $this->assertNull($request->attributes->get('uuid'));
    }

    /**
     * @param class-string|null $class
     *
     * @return ArgumentMetadata&MockObject
     */
    public function createConfiguration(
        ?string $class = null,
        ?string $name = null
    ): ArgumentMetadata {
        $config = $this->createMock(ArgumentMetadata::class);

        if ($name !== null) {
            $config->expects($this->any())
                ->method('getName')
                ->willReturn($name);
        }

        if ($class !== null) {
            $config->expects($this->any())
                ->method('getType')
                ->willReturn($class);
        }

        return $config;
    }
}
