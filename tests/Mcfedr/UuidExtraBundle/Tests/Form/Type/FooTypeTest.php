<?php

declare(strict_types=1);

namespace Mcfedr\UuidExtraBundle\Tests\Form\Type;

use Mcfedr\UuidExtraBundle\Form\Model\Foo;
use Mcfedr\UuidExtraBundle\Form\Type\FooType;
use Mcfedr\UuidExtraBundle\Form\Type\UuidType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class FooTypeTest extends WebTestCase
{
    public function testFoo()
    {
        $client = self::createClient();

        $f = new Foo();

        $form = $client->getContainer()->get('form.factory')
            ->createBuilder(FooType::class, $f)
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
