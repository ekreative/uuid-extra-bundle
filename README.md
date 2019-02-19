# Uuid Extra Bundle

A convenient bundle for using ramsey/uuid in your project

[![Latest Stable Version](https://poser.pugx.org/mcfedr/uuid-extra-bundle/v/stable.png)](https://packagist.org/packages/mcfedr/uuid-extra-bundle)
[![License](https://poser.pugx.org/mcfedr/uuid-extra-bundle/license.png)](https://packagist.org/packages/mcfedr/uuid-extra-bundle)
[![Build Status](https://travis-ci.org/mcfedr/uuid-extra-bundle.svg?branch=master)](https://travis-ci.org/mcfedr/uuid-extra-bundle)

## Install

### Composer

```bash
php composer.phar require mcfedr/uuid-extra-bundle
```

### AppKernel

Include the bundle in your AppKernel

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Mcfedr\UuidExtraBundle\McfedrUuidExtraBundle()
```

## Config

No config needed

## Param Converter

Use just like any other param converter

```php
/**
 * @ParamConverter("uuid", class="Ramsey\Uuid\UuidInterface")
 * @Route("/simple/{uuid}")
 */
public function simpleAction(UuidInterface $uuid)
{
    return new Response($uuid->toString());
}
```

Most of the time its going to work automatically, as long as you use type hinting on your action

```php
/**
 * @Route("/automatic/{uuid}")
 */
public function simpleAction(UuidInterface $uuid)
{
    return new Response($uuid->toString());
}
```
    
Also works for optional params

```php
/**
 * @Route("/optional/{uuid}")
 */
public function simpleAction(UuidInterface $uuid = null)
{
    return new Response($uuid ? $uuid->toString() : 'no uuid');
}
```

## Serializer

Also like a normalizer should

```php
$this->serializer->serialize($uuid, 'json')
```

Results in `"f13a5b20-9741-4b15-8120-138009d8e0c7"`

And the other way around

```php
$this->serializer->denormalize('"f13a5b20-9741-4b15-8120-138009d8e0c7"', UuidInterface::class, 'json')
```

Results in `$uuid`

Works in your Objects etc.

## Form Type

Does everything you'd expect

```php
->add('uuid', UuidType:class)
```

And if your model has

```php
/**
 * @Assert\Uuid
 */
private $uuid;
```

It will automatically use the `UuidType`
