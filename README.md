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

**File: StepA.php**

``` php
use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

class StepA implements SetupStep
{
    public static function dependsOn(): iterable
    {
        return [];
    }

    public static function mustRunBefore(): iterable
    {
        return [];
    }
    
    public function __invoke(): SetupStepResult
    {
        $alreadySetup = false;
        
        if (! $alreadySetup) {
            // Do stuff here...
            return SetupStepResult::succeed('message here');                
        } else {
            return SetupStepResult::skip('already setup message');
        }
    }
    
    public function __toString() : string{
        return 'describe what StepA does';
    }
}
```

**File: StepB.php**

```php
use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

class StepB implements SetupStep
{
    public static function dependsOn(): iterable
    {
        return [StepA::class];
    }

    public static function mustRunBefore(): iterable
    {
        return [StepC::class];
    }
    
    public function __invoke(): SetupStepResult
    {
        $alreadySetup = false;
        $wasRequired = true;
                
        if (! $alreadySetup) {
            try {
                return SetupStepResult::succeed('message here');    
            } catch (\Throwable $e) {
                return SetupStepResult::fail('fail message here', $wasRequired);
            }
                            
        } else {
            return SetupStepResult::skip('already setup message');
        }
    }
    
    public function __toString() : string{
        return 'describe what StepB does';
    }
}
```

**File: SetupRunner.php**

```php

use SetterUpper\SetterUpper;

$su = new SetterUpper();
$su->add(new StepA(), new StepB(), new StepC());
$report = $su->runAll();

```

## In-depth

### Naming

* TODO: Mention that if you try to add the same class twice, a name collision exception will be thrown

### Handing errors

* TODO: Mention that this library does not handle exceptions thrown in SetupSteps

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
