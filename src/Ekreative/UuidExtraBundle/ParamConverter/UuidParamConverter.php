<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\ParamConverter;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UuidParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws NotFoundHttpException When invalid uuid given
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $param = $configuration->getName();

        if (!$request->attributes->has($param)) {
            return false;
        }

        $value = $request->attributes->get($param);

        if (!$value && $configuration->isOptional()) {
            return false;
        }

        try {
            $uuid = Uuid::fromString($value);

            $request->attributes->set($param, $uuid);

            return true;
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException('Invalid uuid given');
        }
    }

    public function supports(ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        return UuidInterface::class === $class || Uuid::class === $class;
    }
}
