# php-gd-config-check

[![Tests and Code Analysis](https://github.com/ChromaticHQ/php-gd-config-check/actions/workflows/ci.yml/badge.svg)](https://github.com/ChromaticHQ/php-gd-config-check/actions/workflows/ci.yml)

While it is possible to check for the presence of the GD plugin via `composer require ext-gd`, this does not ensure that specific format support is enabled. This Composer plugin allows you to specify a set of required formats for GD and will verify that they are enabled after `composer install` has run.

## Configuration
This plugin ships with a default set of required formats that it checks for.

* FreeType
* JPEG
* PNG
* WebP

To override these defaults, include configuration in your `composer.json` file:

```json
"extra": {
  "php-gd-config-check": {
      "required-formatsZZ": [
          "JPEG Support",
          "PNG Support"
      ]
}
```
