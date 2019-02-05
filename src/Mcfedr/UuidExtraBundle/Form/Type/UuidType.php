<?php

declare(strict_types=1);

namespace Mcfedr\UuidExtraBundle\Form\Type;

use Mcfedr\UuidExtraBundle\Form\Transformer\UuidTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UuidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new UuidTransformer());
    }

    public function getParent()
    {
        return TextType::class;
    }
}
