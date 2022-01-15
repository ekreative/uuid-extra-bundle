<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Controller;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TestController
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @ParamConverter("uuid", class="Ramsey\Uuid\UuidInterface")
     * @Route("/simple/{uuid}")
     */
    public function simpleAction(UuidInterface $uuid): Response
    {
        return new Response($uuid->toString());
    }

    /**
     * @ParamConverter("uuid", class="Ramsey\Uuid\UuidInterface")
     * @Route("/optional/{uuid}")
     */
    public function optionalAction(UuidInterface $uuid = null): Response
    {
        return new Response($uuid ? $uuid->toString() : null);
    }

    /**
     * @Route("/automatic/{uuid}")
     */
    public function automaticAction(UuidInterface $uuid): Response
    {
        return new Response($uuid->toString());
    }

    /**
     * @Route("/optionalAutomatic/{uuid}")
     */
    public function optionalAutomaticAction(UuidInterface $uuid = null): Response
    {
        return new Response($uuid ? $uuid->toString() : null);
    }

    /**
     * @Route("/serialized")
     */
    public function serializeAction(): Response
    {
        return new Response($this->serializer->serialize(Uuid::fromString('f13a5b20-9741-4b15-8120-138009d8e0c7'), 'json'));
    }
}
