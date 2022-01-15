<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form\Type;

use Ekreative\UuidExtraBundle\Form\Transformer\UuidTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UuidType extends AbstractType
{
    /** {@inheritDoc} */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new UuidTransformer());
    }

    /** @return class-string<TextType> */
    public function getParent(): string
    {
        return TextType::class;
    }
}
