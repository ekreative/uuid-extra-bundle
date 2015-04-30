<?php

namespace Mcfedr\UuidParamConverterBundle\Tests\ParamConverter;

use Mcfedr\UuidParamConverterBundle\ParamConverter\UuidParamConverter;
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
        $config = $this->createConfiguration("Rhumsaa\\Uuid\\Uuid");
        $this->assertTrue($this->converter->supports($config));

        $config = $this->createConfiguration(__CLASS__);
        $this->assertFalse($this->converter->supports($config));

        $config = $this->createConfiguration();
        $this->assertFalse($this->converter->supports($config));
    }

    public function testApply()
    {
        $request = new Request(array(), array(), array('uuid' => 'f13a5b20-9741-4b15-8120-138009d8e0c7'));
        $config = $this->createConfiguration("Rhumsaa\\Uuid\\Uuid", "uuid");

        $this->converter->apply($request, $config);

        $this->assertInstanceOf("Rhumsaa\\Uuid\\Uuid", $request->attributes->get('uuid'));
        $this->assertEquals('f13a5b20-9741-4b15-8120-138009d8e0c7', $request->attributes->get('uuid')->toString());
    }

    public function testApplyInvalidUuid404Exception()
    {
        $request = new Request(array(), array(), array('uuid' => 'Invalid uuid Format'));
        $config = $this->createConfiguration("Rhumsaa\\Uuid\\Uuid", "uuid");

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', 'Invalid uuid given');
        $this->converter->apply($request, $config);
    }

    public function testApplyOptionalWithEmptyAttribute()
    {
        $request = new Request(array(), array(), array('uuid' => null));
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
            ->setMethods(array('getClass', 'getAliasName', 'getOptions', 'getName', 'allowArray', 'isOptional'))
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
