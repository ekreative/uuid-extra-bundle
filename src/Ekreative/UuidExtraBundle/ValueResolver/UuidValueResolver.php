<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\ValueResolver;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function gettype;
use function is_string;
use function sprintf;

class UuidValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (! $this->supports($argument)) {
            return [];
        }

        $param = $argument->getName();

        if (! $request->attributes->has($param)) {
            return [];
        }

        $value = $request->attributes->get($param);

        if (! $value && $argument->isNullable()) {
            return [];
        }

        if (! is_string($value)) {
            throw new NotFoundHttpException(sprintf(
                'Invalid uuid given - expected "string", "%s" given',
                gettype($value)
            ));
        }

        try {
            return [Uuid::fromString($value)];
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException('Invalid uuid given');
        }
    }

    public function supports(ArgumentMetadata $argument): bool
    {
        $class = $argument->getType();

        return $class === UuidInterface::class || $class === Uuid::class;
    }
}
