<?php

declare(strict_types=1);

namespace Mcfedr\UuidExtraBundle\Tests\Form\Type;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Tests\Extension\Core\Type\BaseTypeTest;

class UuidTypeTest extends BaseTypeTest
{
    const TESTED_TYPE = 'Mcfedr\UuidExtraBundle\Form\Type\UuidType';

    public function testSubmitCastsToInteger()
    {
        $form = $this->factory->create(static::TESTED_TYPE);

        $form->submit('f13a5b20-9741-4b15-8120-138009d8e0c7');

        $this->assertEquals(Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7'), $form->getData());
        $this->assertSame('f13a5b20-9741-4b15-8120-138009d8e0c7', $form->getViewData());
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null)
    {
        parent::testSubmitNull($expected, $norm, '');
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'f13a5b20-9741-4b15-8120-138009d8e0c7', $expectedData = 'f13a5b20-9741-4b15-8120-138009d8e0c7')
    {
        $expectedData = Uuid::fromString($expectedData);

        $form = $this->factory->create(static::TESTED_TYPE, null, [
            'empty_data' => $emptyData,
        ]);
        $form->submit(null);

        $this->assertEquals($emptyData, $form->getViewData());
        $this->assertEquals($expectedData, $form->getNormData());
        $this->assertEquals($expectedData, $form->getData());
    }
}
