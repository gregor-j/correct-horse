# correct horse passphrase generator

[![License: MIT]](LICENSE)
[![Packagist Version]][packagist]
[![CI-svg]][CI-pipeline]

A random passphrase generator inspired by Randall Munroes [XKCD #936], [bbusschots/hsxkpasswd], and [matt-allan/battery-staple]. Thanks!

Requires PHP 7.2 or newer. The CI pipeline tests PHP 7.2, 7.4, and 8.0 through 8.4.

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

All development commands (install, test, lint, beautify, sniff, audit,
validate) run via Docker through the `Makefile`, so no local PHP installation is
needed. Run `make help` to list all targets. Most targets require `PHP_VERSION`,
e.g.:

```sh
make install PHP_VERSION=7.2
```

Individual targets are available for `make install`, `make validate`, `make audit`,
`make lint`, `make sniff`, `make beautify`, and `make test`. To verify the lowest
allowed dependency versions, run `make lowest`.

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
