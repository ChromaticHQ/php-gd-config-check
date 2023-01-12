# php-gd-config-check

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
