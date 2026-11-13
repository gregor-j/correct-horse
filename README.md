# correct horse passphrase generator

[![License: MIT]](LICENSE)
[![Packagist Version]][packagist]
[![CI-svg]][CI-pipeline]

A random passphrase generator inspired by Randall Munroes [XKCD #936], [bbusschots/hsxkpasswd], and [matt-allan/battery-staple]. Thanks!

Requires PHP `^8.1` (PHP 8.1 through 8.x, excluding PHP 9). Raising the minimum
from PHP 7.2 is a breaking change. CI tests PHP 8.1 and 8.5 with normal and
lowest dependencies, and retains PHP 8.2–8.4 checks during the transition.

## Why??

I needed a generator for handing out initial passwords in PHP. The password should not be easy to guess, especially not for a computer, and the person receiving the password should be able to enter the password fast and without mistakes.

## The comic that inspired a lot of people

[![To anyone who understands information theory and security and is in an infuriating argument with someone who does not (possibly involving mixed case), I sincerely apologize.][XKCD #936 image]][XKCD #936]

## Dictionary

This repository contains a German word list based on the GPL-licensed German dictionary for WinEdit by Juergen Vierheilig, copied from [Crypt::HSXKPasswd::Dictionary::DE]. I tried to rid the word list of NSFW words.

* ['dict-de-lc.txt'](dict/dict-de-lc.txt) List of lower case German words.
* ['dict-de-uc.txt'](dict/dict-de-uc.txt) List of upper case German words.

In order to add dictionaries, either implement the [`\GregorJ\CorrectHorse\DictionaryInterface`](src/DictionaryInterface.php) or just copy files into the [dict](dict) directory and use [`\GregorJ\CorrectHorse\Dictionaries\DictionaryFile`](src/Dictionaries/DictionaryFile.php).

## Usage

```bash
composer require gregorj/correct-horse
```

```php
<?php

require_once 'vendor/autoload.php';

use GregorJ\CorrectHorse\Dictionaries\DictionaryFile;
use GregorJ\CorrectHorse\Generators\RandomNumbers;
use GregorJ\CorrectHorse\Generators\RandomWord;
use GregorJ\CorrectHorse\Generators\RandomWords;
use GregorJ\CorrectHorse\Generators\RandomCharacter;
use GregorJ\CorrectHorse\PassphraseGenerator;

$passphrase = new PassphraseGenerator();
// separate the random items of the passphrase with a special character
$passphrase->setSeparator(new RandomCharacter(['-', '#', '.', ' ']));
// random numbers should be between 1 and 99
$randomNumbers = new RandomNumbers();
$randomNumbers->setMinAndMax(1,99);
// add one random number at the beginning
$passphrase->add($randomNumbers, 1);
// add 4 random lower or upper case words
$passphrase->add(
    new RandomWords(
        new RandomWord(new DictionaryFile('dict-de-lc.txt')),
        new RandomWord(new DictionaryFile('dict-de-uc.txt'))
    ),
    4
);
// finally, add another random number
$passphrase->add($randomNumbers, 1);
// now let's generate a random passphrase
echo $passphrase->generate().PHP_EOL;
//9 korrekt Pferd Batterie Heftklammer 36
```

## Development

Development commands run through the Docker-backed `Makefile`; PHP need not be
installed locally. Run `make help` for all targets.

```sh
make check PHP_VERSION=8.1
make check PHP_VERSION=8.5 DEPENDENCIES_LOWEST=1
```

`check` resolves dependencies, runs lint, PHPUnit and PHPCS, then audits the
same temporary lockfile. `install` removes its temporary lockfile, so a later
standalone `audit` does not verify that earlier snapshot. `clean` removes
`vendor/` and the PHPUnit cache, not a pre-existing lockfile. Composer uses an
isolated manifest copy in the project root and does not change the checked-in
Composer configuration. The host needs `flock` to serialize install, check,
audit and clean operations sharing `vendor/`.

The Composer executable comes from `COMPOSER_IMAGE` (default `composer:2`);
Composer and QA run under the requested PHP image. The container installs
`git` and `unzip` for dependency resolution. Proxy environment variables and an
optional `CA_CERT_FILE` are forwarded, and `COMPOSER_CACHE_DIR` controls the
cache. If `config.platform.php` is present, only the isolated manifest copy
uses the actual PHP patch version; other platform settings and the checked-in
manifest remain unchanged.

Changes to `main` use pull requests and squash merges. Issue branches should be
named `<issue-number>-<title-slug>`. The force-push ruleset excludes the broader
glob `[0-9]*-*`, which also matches some names outside that convention; it does
not enforce the PR source. Main is protected separately.

[XKCD #936 image]: https://imgs.xkcd.com/comics/password_strength.png
[XKCD #936]: https://xkcd.com/936/
[matt-allan/battery-staple]: https://github.com/matt-allan/battery-staple
[bbusschots/hsxkpasswd]: https://github.com/bbusschots/hsxkpasswd
[Crypt::HSXKPasswd::Dictionary::DE]: https://bbusschots.github.io/hsxkpasswd/Crypt-HSXKPasswd/pod.html#Crypt::HSXKPasswd::Dictionary::DE
[License: MIT]: https://img.shields.io/badge/license-MIT-blue.svg
[Packagist Version]: https://img.shields.io/packagist/v/gregorj/correct-horse
[packagist]: https://packagist.org/packages/gregorj/correct-horse
[CI-svg]: https://github.com/gregor-j/correct-horse/actions/workflows/php.yml/badge.svg
[CI-pipeline]: https://github.com/gregor-j/correct-horse/actions/workflows/php.yml
