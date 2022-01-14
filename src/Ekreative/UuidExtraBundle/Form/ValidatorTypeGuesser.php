<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form;

use Ekreative\UuidExtraBundle\Form\Type\UuidType;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Uuid;

class ValidatorTypeGuesser extends \Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser
{
    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property): ?TypeGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessTypeForConstraint($constraint);
        });
    }

    public function guessRequired($class, $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessRequiredForConstraint($constraint);
        // If we don't find any constraint telling otherwise, we can assume
            // that a field is not required (with LOW_CONFIDENCE)
        }, false);
    }

    public function guessMaxLength($class, $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessMaxLengthForConstraint($constraint);
        });
    }

    public function guessPattern($class, $property): ?ValueGuess
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessPatternForConstraint($constraint);
        });
    }

    public function guessTypeForConstraint(Constraint $constraint): ?TypeGuess
    {
        if (\get_class($constraint) !== Uuid::class) {
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
        if (\get_class($constraint) !== Uuid::class) {
            return new ValueGuess(36, Guess::HIGH_CONFIDENCE);
        }

        return null;
    }

    public function guessPatternForConstraint(Constraint $constraint): ?ValueGuess
    {
        if (\get_class($constraint) !== Uuid::class) {
            return new ValueGuess(\Ramsey\Uuid\Uuid::VALID_PATTERN, Guess::HIGH_CONFIDENCE);
        }

        return null;
    }
}
