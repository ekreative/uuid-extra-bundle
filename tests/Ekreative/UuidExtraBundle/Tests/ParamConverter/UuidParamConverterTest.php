<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Tests\ParamConverter;

use Ekreative\UuidExtraBundle\ParamConverter\UuidParamConverter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UuidParamConverterTest extends TestCase
{
    /** @var UuidParamConverter */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new UuidParamConverter();
    }

    public function testSupports(): void
    {
        $config = $this->createConfiguration(Uuid::class);
        $this->assertTrue($this->converter->supports($config));

        $config = $this->createConfiguration(UuidInterface::class);
        $this->assertTrue($this->converter->supports($config));

        $config = $this->createConfiguration(__CLASS__);
        $this->assertFalse($this->converter->supports($config));

        $config = $this->createConfiguration();
        $this->assertFalse($this->converter->supports($config));
    }

    public function testApply(): void
    {
        $request = new Request([], [], ['uuid' => 'f13a5b20-9741-4b15-8120-138009d8e0c7']);
        $config = $this->createConfiguration(Uuid::class, 'uuid');

        $this->converter->apply($request, $config);

        $this->assertEquals(
            Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7'),
            $request->attributes->get('uuid')
        );
    }

    public function testApplyInvalidValueTypeForUuidWillLeadToA404Exception(): void
    {
        $request = new Request([], [], ['uuid' => ['an', 'array', 'instead', 'of', 'a', 'string']]);
        $config = $this->createConfiguration(Uuid::class, 'uuid');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Invalid uuid given - expected "string", "array" given');
        $this->converter->apply($request, $config);
    }

    public function testApplyInvalidUuid404Exception(): void
    {
        $request = new Request([], [], ['uuid' => 'Invalid uuid Format']);
        $config = $this->createConfiguration(Uuid::class, 'uuid');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Invalid uuid given');
        $this->converter->apply($request, $config);
    }

    public function testApplyOptionalWithEmptyAttribute(): void
    {
        $request = new Request([], [], ['uuid' => null]);
        $config = $this->createConfiguration(\DateTime::class, 'uuid');
        $config->expects($this->once())
            ->method('isOptional')
            ->willReturn(true);

        $this->assertFalse($this->converter->apply($request, $config));
        $this->assertNull($request->attributes->get('uuid'));
    }

    /**
     * @param class-string|null $class
     *
     * @return ParamConverter&MockObject
     */
    public function createConfiguration(
        ?string $class = null,
        ?string $name = null
    ): ParamConverter {
        $config = $this->createMock(ParamConverter::class);

        if (null !== $name) {
            $config->expects($this->any())
                ->method('getName')
                ->willReturn($name);
        }
        if (null !== $class) {
            $config->expects($this->any())
                ->method('getClass')
                ->willReturn($class);
        }

        return $config;
    }
}
