# AGENTS.md

## Project overview
- `correct-horse` is a small PHP library for generating passphrases inspired by XKCD #936.
- The main orchestration happens in `src/PassphraseGenerator.php`: it collects `RandomGeneratorInterface` implementations, runs each generator, then joins the generated parts with a separator.
- Generators are intentionally small and composable; prefer wiring existing pieces over adding new layers.

## Core structure
- `src/RandomGeneratorInterface.php` defines the common contract: `reset()`, `add()`, `remove()`, `has()`, `get()`.
- Shared behavior lives in traits under `src/Generators/`:
  - `ManageRandomItemsTrait` stores items, shuffles on `get()`, and removes random entries with `random_int()`.
  - `DictionaryWordTrait` prevents duplicate words before appending them.
- Concrete generators are `final` classes such as `RandomWord`, `RandomWords`, `RandomNumbers`, and `RandomCharacter`.

## Dictionary and data flow
- Dictionary-backed words come from `src/Dictionaries/DictionaryFile.php`, which reads files from the repo-local `dict/` directory.
- The shipped dictionaries are `dict/dict-de-lc.txt` and `dict/dict-de-uc.txt`.
- `RandomWords` combines lower- and upper-case `RandomWord` instances and tries to keep both cases represented when possible.
- `RandomCharacter` uses a small default separator set (`-`, `#`, `.`, `,`, `+`, `;`, `*`) unless custom chars are provided.

## Development workflow
- Autoloading is PSR-4 from `composer.json`: `GregorJ\CorrectHorse\` → `src/`, tests use `Tests\GregorJ\CorrectHorse\` → `tests/`.
- PHP `^8.1` is required. CI tests PHP 8.1 and 8.5 with normal and lowest dependencies, and retains PHP 8.2–8.4 checks during the transition.
- Run all QA and audit one resolved snapshot with `make check PHP_VERSION=8.1` or `make check PHP_VERSION=8.5 DEPENDENCIES_LOWEST=1`.
- Available QA targets include `make lint`, `make sniff`, `make beautify`, `make audit`, and `make validate`, each accepting `PHP_VERSION` where required. `make beautify` runs PHPCBF before `make sniff` checks the PSR-12 rules in `phpcs.xml`.
- See README for the PHP matrix, locking, proxy and CA configuration.
- The PHPUnit config in `phpunit.xml` boots `vendor/autoload.php`, runs `tests/*Test.php`, and configures `src/` as the coverage source.

## Codebase conventions
- Keep new production classes `final` unless the repo already uses another pattern.
- Prefer constructor injection, explicit interfaces, and small traits over inheritance.
- Preserve the existing randomness semantics: methods often return early when nothing can be generated instead of throwing.
- Keep docblocks and comments brief; existing code uses straightforward PHP 7.2-style signatures and typed returns where available.

## Useful reference files
- `src/PassphraseGenerator.php` — orchestration and separator handling.
- `src/Generators/RandomWords.php` — mixed-case composition logic.
- `src/Generators/RandomNumbers.php` and `src/Generators/RandomCharacter.php` — random item generation patterns.
- `tests/PassphraseGeneratorTest.php` — expected generator/separator call order.
- `tests/RandomWordTest.php` — duplicate-word behavior and dictionary mocking.
