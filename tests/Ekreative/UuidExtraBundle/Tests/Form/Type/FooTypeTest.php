<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Tests\Form\Type;

use Ekreative\UuidExtraBundle\Form\Model\Foo;
use Ekreative\UuidExtraBundle\Form\Type\FooType;
use Ekreative\UuidExtraBundle\Form\Type\UuidType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FooTypeTest extends WebTestCase
{
    public function testFoo(): void
    {
        $client = self::createClient();

        $f = new Foo();

        $factory = $client->getContainer()
            ->get('test.form.factory');

        \assert($factory instanceof FormFactoryInterface);

        $form = $factory->createBuilder(FooType::class, $f)
            ->getForm();

        $element = $form->get('uuid');
        $this->assertInstanceOf(UuidType::class, $element->getConfig()->getType()->getInnerType());

        $request = Request::create('/', 'POST', [
            'foo' => [
                'uuid' => '5b10f27c-8a8f-4cd4-9d0b-1ff2f8d7a268',
            ],
        ]);

        $form->handleRequest($request);
        $this->assertInstanceOf(UuidInterface::class, $f->getUuid());
    }
}
