<?php

declare(strict_types=1);

namespace Mcfedr\UuidExtraBundle\Form\Type;

use Mcfedr\UuidExtraBundle\Form\Model\Foo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uuid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Foo::class,
        ]);
    }
}
