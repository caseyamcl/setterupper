# Setter-Upper

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is a library to simplify setup workflow for your applications.  Features:

* Every setup step is defined in its own class.
* Supports dependencies between setup steps via `dependsOn()` *and* `mustRunBefore()`
* Well-defined interfaces and simple reporting with the `SetupStepResult()` class
* PSR-4 and PSR-12 compliant

## Install

Via Composer

``` bash
$ composer require caseyamcl/setterupper
```

## Usage

The tl;dr version:

``` php
// TODO: Add example here
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email caseyamcl@gmail.com instead of using the issue tracker.

## Credits

- [Casey McLaughlin][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/caseyamcl/setterupper.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/caseyamcl/setterupper/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/caseyamcl/setterupper.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/caseyamcl/setterupper.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/caseyamcl/setterupper.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/caseyamcl/setterupper
[link-travis]: https://travis-ci.org/caseyamcl/setterupper
[link-scrutinizer]: https://scrutinizer-ci.com/g/caseyamcl/setterupper/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/caseyamcl/setterupper
[link-downloads]: https://packagist.org/packages/caseyamcl/setterupper
[link-author]: https://github.com/caseyamcl
[link-contributors]: ../../contributors
<!-- @IGNORE PREVIOUS: link -->
