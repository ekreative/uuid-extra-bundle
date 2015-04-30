# Uuid Param Converter Bundle

A convenient bundle for using ramsey/uuid in your controllers

[![Latest Stable Version](https://poser.pugx.org/mcfedr/uuid-paramconverter/v/stable.png)](https://packagist.org/packages/mcfedr/uuid-paramconverter)
[![License](https://poser.pugx.org/mcfedr/uuid-paramconverter/license.png)](https://packagist.org/packages/mcfedr/uuid-paramconverter)
[![Build Status](https://travis-ci.org/mcfedr/uuid-paramconverter.svg?branch=master)](https://travis-ci.org/mcfedr/uuid-paramconverter)

## Install

### Composer

    php composer.phar require mcfedr/uuid-paramconverter

### AppKernel

Include the bundle in your AppKernel

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Mcfedr\UuidParamConverterBundle\McfedrUuidParamConverterBundle()

## Config

No config needed

## Usage

Use just like any other param converter

    /**
     * @ParamConverter("uuid", class="Rhumsaa\Uuid\Uuid")
     * @Route("/simple/{uuid}")
     */
    public function simpleAction(Uuid $uuid)
    {
        return new Response($uuid->toString());
    }
