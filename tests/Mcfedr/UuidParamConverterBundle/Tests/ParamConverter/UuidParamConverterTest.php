<?php

namespace Mcfedr\UuidParamConverterBundle\Tests\ParamConverter;

use Mcfedr\UuidParamConverterBundle\ParamConverter\UuidParamConverter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;

class UuidParamConverterTest extends \PHPUnit_Framework_TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new UuidParamConverter();
    }

    public function testSupports()
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

    public function testApply()
    {
        $request = new Request([], [], ['uuid' => 'f13a5b20-9741-4b15-8120-138009d8e0c7']);
        $config = $this->createConfiguration(Uuid::class, 'uuid');

        $this->converter->apply($request, $config);

        $this->assertInstanceOf(Uuid::class, $request->attributes->get('uuid'));
        $this->assertEquals('f13a5b20-9741-4b15-8120-138009d8e0c7', $request->attributes->get('uuid')->toString());
    }

    public function testApplyInvalidUuid404Exception()
    {
        $request = new Request([], [], ['uuid' => 'Invalid uuid Format']);
        $config = $this->createConfiguration(Uuid::class, 'uuid');

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', 'Invalid uuid given');
        $this->converter->apply($request, $config);
    }

    public function testApplyOptionalWithEmptyAttribute()
    {
        $request = new Request([], [], ['uuid' => null]);
        $config = $this->createConfiguration('DateTime', 'uuid');
        $config->expects($this->once())
            ->method('isOptional')
            ->will($this->returnValue(true));

        $this->assertFalse($this->converter->apply($request, $config));
        $this->assertNull($request->attributes->get('uuid'));
    }

    public function createConfiguration($class = null, $name = null)
    {
        $config = $this
            ->getMockBuilder('Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter')
            ->setMethods(['getClass', 'getAliasName', 'getOptions', 'getName', 'allowArray', 'isOptional'])
            ->disableOriginalConstructor()
            ->getMock();

        if ($name !== null) {
            $config->expects($this->any())
                ->method('getName')
                ->will($this->returnValue($name));
        }
        if ($class !== null) {
            $config->expects($this->any())
                ->method('getClass')
                ->will($this->returnValue($class));
        }

        return $config;
    }
}
