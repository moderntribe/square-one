# PHPStan

[PHPStan](https://phpstan.org/user-guide/getting-started) is a Static Analysis Tool for PHP that is used to check for errors in our code, and it is also used to help us write more secure code.

PHPStan runs through the `PHP Composer, Codesniffer, and Static Analysis` GitHub Action on all pull requests and should be passing before requesting a Code Review. All new code added to SquareOne must meet [rule level of 5](https://phpstan.org/user-guide/rule-levels). We will increase the rule level over time as we improve the level of type-hinting we use across the project. The code is not currently up to rule level 5, and we are including a baseline file that ignores the few remaining errors so that the new code is kept up to the standard we expect moving forward.

## Getting Started

To run PHPStan locally, you need to make sure that you have run `composer install` and then run `vendor/bin/phpstan --memory-limit=512M` from the project's root. The first time the tool is run, it can take a minute or two to pull together all the stubs and run the analysis. A cache is created in the `.phpstan-cache` direction, which will allow for future runs to only be on the files that have changed.

### PhpStorm

Details on PhpStorm's integration with PHPStan can be found [here](https://www.jetbrains.com/help/phpstorm/using-phpstan.html).

### Visual Studio Code

You can set up the [PHP Static Analysis](https://marketplace.visualstudio.com/items?itemName=breezelin.phpstan) extension to run PHPStan on individual files.
