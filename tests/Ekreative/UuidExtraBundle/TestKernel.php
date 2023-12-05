<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle;

use Ekreative;
use Symfony;

class TestKernel extends Symfony\Component\HttpKernel\Kernel
{
    /** {@inheritDoc} */
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Ekreative\UuidExtraBundle\EkreativeUuidExtraBundle(),
        ];
    }

    public function registerContainerConfiguration(Symfony\Component\Config\Loader\LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../../config_test.yml');
    }
}
