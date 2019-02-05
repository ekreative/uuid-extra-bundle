# Uuid Extra Bundle

A convenient bundle for using ramsey/uuid in your controllers

[![Latest Stable Version](https://poser.pugx.org/mcfedr/uuid-extra-bundle/v/stable.png)](https://packagist.org/packages/mcfedr/uuid-extra-bundle)
[![License](https://poser.pugx.org/mcfedr/uuid-extra-bundle/license.png)](https://packagist.org/packages/mcfedr/uuid-extra-bundle)
[![Build Status](https://travis-ci.org/mcfedr/uuid-extra-bundle.svg?branch=master)](https://travis-ci.org/mcfedr/uuid-extra-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/97f6fe7c-375f-4ba1-b222-700a81bd3b65/mini.png)](https://insight.sensiolabs.com/projects/97f6fe7c-375f-4ba1-b222-700a81bd3b65)

## Install

### Composer

    php composer.phar require mcfedr/uuid-extra-bundle

### AppKernel

Include the bundle in your AppKernel

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Mcfedr\UuidExtraBundle\McfedrUuidParamConverterBundle()

## Config

No config needed

## Usage

Use just like any other param converter

    /**
     * @ParamConverter("uuid", class="Ramsey\Uuid\Uuid")
     * @Route("/simple/{uuid}")
     */
    public function simpleAction(UuidInterface $uuid)
    {
        return new Response($uuid->toString());
    }

Most of the time its going to work automatically, as long as you use type hinting on your action

    /**
     * @Route("/automatic/{uuid}")
     */
    public function simpleAction(UuidInterface $uuid)
    {
        return new Response($uuid->toString());
    }
    
Also works for optional params

    /**
     * @Route("/optional/{uuid}")
     */
    public function simpleAction(UuidInterface $uuid = null)
    {
        return new Response($uuid ? $uuid->toString() : 'no uuid');
    }
