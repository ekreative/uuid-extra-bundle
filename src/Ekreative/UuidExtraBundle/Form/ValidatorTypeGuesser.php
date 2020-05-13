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
    public function guessType($class, $property)
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessTypeForConstraint($constraint);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function guessRequired($class, $property)
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessRequiredForConstraint($constraint);
        // If we don't find any constraint telling otherwise, we can assume
            // that a field is not required (with LOW_CONFIDENCE)
        }, false);
    }

    /**
     * {@inheritdoc}
     */
    public function guessMaxLength($class, $property)
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessMaxLengthForConstraint($constraint);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function guessPattern($class, $property)
    {
        return $this->guess($class, $property, function (Constraint $constraint) {
            return $this->guessPatternForConstraint($constraint);
        });
    }

    /**
     * Guesses a field class name for a given constraint.
     *
     * @return TypeGuess|null The guessed field class and options
     */
    public function guessTypeForConstraint(Constraint $constraint)
    {
        switch (\get_class($constraint)) {
            case Uuid::class:
                return new TypeGuess(UuidType::class, [], Guess::HIGH_CONFIDENCE);
        }
    }

    /**
     * Guesses whether a field is required based on the given constraint.
     *
     * @return ValueGuess|null The guess whether the field is required
     */
    public function guessRequiredForConstraint(Constraint $constraint)
    {
    }

    /**
     * Guesses a field's maximum length based on the given constraint.
     *
     * @return ValueGuess|null The guess for the maximum length
     */
    public function guessMaxLengthForConstraint(Constraint $constraint)
    {
        switch (\get_class($constraint)) {
            case Uuid::class:
                return new ValueGuess(36, Guess::HIGH_CONFIDENCE);
        }
    }

    /**
     * Guesses a field's pattern based on the given constraint.
     *
     * @return ValueGuess|null The guess for the pattern
     */
    public function guessPatternForConstraint(Constraint $constraint)
    {
        switch (\get_class($constraint)) {
            case Uuid::class:
                return new ValueGuess(\Ramsey\Uuid\Uuid::VALID_PATTERN, Guess::HIGH_CONFIDENCE);
        }
    }
}
