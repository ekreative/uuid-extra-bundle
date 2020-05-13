<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form\Model;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Foo
{
    /**
     * @var ?UuidInterface
     *
     * @Assert\Uuid
     */
    private $uuid;

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }
}
