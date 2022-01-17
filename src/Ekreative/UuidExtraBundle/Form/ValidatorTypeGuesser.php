<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form;

use Closure;
use Ekreative\UuidExtraBundle\Form\Type\UuidType;
use Ramsey\Uuid\Rfc4122\Validator;
use Ramsey\Uuid\Validator\ValidatorInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;

use function get_class;

class ValidatorTypeGuesser extends \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser
{
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(MetadataFactoryInterface $metadataFactory, ?ValidatorInterface $validator = null)
    {
        parent::__construct($metadataFactory);

        $this->validator = $validator ?? new Validator();
    }

    public function guessType(string $class, string $property): ?TypeGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint): ?TypeGuess {
            return $this->guessTypeForConstraint($constraint);
        });
    }

    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint): ?ValueGuess {
            return $this->guessRequiredForConstraint($constraint);

        // If we don't find any constraint telling otherwise, we can assume
            // that a field is not required (with LOW_CONFIDENCE)
        }, false);
    }

    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint): ?ValueGuess {
            return $this->guessMaxLengthForConstraint($constraint);
        });
    }

    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint): ?ValueGuess {
            return $this->guessPatternForConstraint($constraint);
        });
    }

    public function guessTypeForConstraint(Constraint $constraint): ?TypeGuess
    {
        if (get_class($constraint) !== Uuid::class) {
            return null;
        }

        return new TypeGuess(UuidType::class, [], Guess::HIGH_CONFIDENCE);
    }

    /** @return null */
    public function guessRequiredForConstraint(Constraint $constraint): ?ValueGuess
    {
        return null;
    }

    public function guessMaxLengthForConstraint(Constraint $constraint): ?ValueGuess
    {
        if (get_class($constraint) !== Uuid::class) {
            return new ValueGuess(36, Guess::HIGH_CONFIDENCE);
        }

        return null;
    }

    public function guessPatternForConstraint(Constraint $constraint): ?ValueGuess
    {
        if (get_class($constraint) !== Uuid::class) {
            return new ValueGuess($this->validator->getPattern(), Guess::HIGH_CONFIDENCE);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param Closure(Constraint ): T $closure
     * @param mixed                   $defaultValue
     *
     * @return T|null
     *
     * @template T of Guess|null
     * @psalm-suppress MoreSpecificImplementedParamType this method is really just in place to better specify the
     *                 definition of {@see \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser::guess()}
     * @psalm-suppress InvalidReturnStatement the parent definition returns a type that is too generic
     * @psalm-suppress InvalidReturnType the parent definition returns a type that is too generic
     */
    protected function guess(string $class, string $property, Closure $closure, $defaultValue = null): ?Guess
    {
        return parent::guess($class, $property, $closure, $defaultValue);
    }
}
