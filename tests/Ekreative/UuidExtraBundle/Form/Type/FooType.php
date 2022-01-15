<?php

declare(strict_types=1);

namespace Ekreative\UuidExtraBundle\Form\Type;

use Ekreative\UuidExtraBundle\Form\Model\Foo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Foo::class,
        ]);
    }
}
