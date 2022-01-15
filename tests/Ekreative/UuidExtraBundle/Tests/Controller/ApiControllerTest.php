<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testSimple(): void
    {
        $client = self::createClient();

        $client->request('GET', '/simple/f13a5b20-9741-4b15-8120-138009d8e0c7');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testOptional(): void
    {
        $client = self::createClient();

        $client->request('GET', '/optional/f13a5b20-9741-4b15-8120-138009d8e0c7');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testOptionalNull(): void
    {
        $client = self::createClient();

        $client->request('GET', '/optional');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAutomatic(): void
    {
        $client = self::createClient();

        $client->request('GET', '/optionalAutomatic/f13a5b20-9741-4b15-8120-138009d8e0c7');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testOptionalAutomatic(): void
    {
        $client = self::createClient();

        $client->request('GET', '/optionalAutomatic/f13a5b20-9741-4b15-8120-138009d8e0c7');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testOptionalAutomaticNull(): void
    {
        $client = self::createClient();

        $client->request('GET', '/optionalAutomatic');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSerializer(): void
    {
        $client = self::createClient();

        $client->request('GET', '/serialized');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('"f13a5b20-9741-4b15-8120-138009d8e0c7"', $client->getResponse()->getContent());
    }
}
