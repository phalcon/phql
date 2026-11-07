# PHQL: Phalcon Query Language

[![Latest Version][packagist-version-badge]][packagist-version-link]
[![PHP Version][php-version-badge]][packagist-version-link]
[![Total Downloads][packagist-downloads-badge]][packagist-downloads-link]
[![License][license-badge]][license-link]

[![Phalcon PHQL CI][phalcon-ci-badge]][phalcon-ci-link]
[![Quality Gate Status][sonar-quality-badge]][sonar-link]
[![Coverage][sonar-coverage-badge]][sonar-link]
[![PDS Skeleton][pds-skeleton-badge]][pds-skeleton-link]

[![Discord][discord-badge]][discord-link]
[![Contributors][contributors-badge]][contributors-link]
[![OpenCollective Backers][oc-backers-badge]][backers-link]
[![OpenCollective Sponsors][oc-sponsors-badge]][sponsors-link]

This repository consists of the PHQL parser, for the Phalcon Query Language used by the Phalcon Framework.

## What this is

Phalcon v6 is a pure-PHP rewrite of [cphalcon](https://github.com/phalcon/cphalcon),
the C extension behind Phalcon v5. This package is the PHQL parser for v6, and
it is a behavioural port of cphalcon's: the same intermediate representation
(key order and integer types included), the same error messages on the same
tokens, and the same limit on how deeply a statement may nest. A statement that
parses (or fails) one way under v5 parses (or fails) the same way here.

The scanner and the parser are written by hand. cphalcon generates its own from
a re2c scanner and a Lemon grammar; earlier versions of this package carried PHP
transliterations of those generated artefacts.

## Requirements

* PHP `>= 8.1 < 9.0`; no extensions

## Installation

```bash
composer require phalcon/phql
```

## Usage

```php
use Phalcon\Phql\Parser;

$ast = (new Parser())->parse('SELECT r.name FROM Robots r WHERE r.id = :id:');
```

`parse()` returns the statement's AST, `null` for a statement without any token
(empty, blank, or starting with a NUL byte), or throws `Phalcon\Phql\Exception`
with cphalcon's message. `setEnableLiterals(false)` rejects numbers, strings,
`TRUE` and `FALSE` (cphalcon's `phalcon.orm.enable_literals`). The node `type`
values are the cases of `Phalcon\Phql\Scanner\Opcode`.

The parser keeps no state between calls. cphalcon's per-request AST cache and
the `id` it adds to each AST belong to `Phalcon\Mvc\Model\Query\Lang` in
phalcon/phalcon, which also turns parse errors into
`Phalcon\Mvc\Model\Exception`.

## How it works

| Class | Role | cphalcon counterpart |
|---|---|---|
| `Scanner\Scanner` | Turns the statement into tokens, one per call | `scanner.re` |
| `Parser\TokenStream` | Feeds the parser one token at a time, runs the literal and opcode checks, builds every error message | the token loop in `base.c`, `%syntax_error` |
| `Parser\StatementParser` | Statements and clauses, by recursive descent - one method per group of grammar rules | `parser.php.lemon` |
| `Parser\ExpressionParser` | Expressions, by precedence climbing over the grammar's own precedence table | `parser.php.lemon` |
| `Parser\Cursor` | One token of lookahead plus a count of Lemon's parser stack | the generated `parser.c` |
| `Parser\Ast` | Builds the nodes | `parser.php.inc.h` |

Tokens are scanned on demand rather than up front: cphalcon checks each token
(literals, unknown opcodes) and reports scanning errors only when it reaches
them, so a later token must not pre-empt an error cphalcon reports earlier.

Scanning works on bytes with `strspn()`/`strcspn()` runs from the cursor, and
the grammar's left-recursive lists and left-associative operator chains are
loops, so parsing time grows linearly with the statement (a 40,000-item `IN`
list parses in about 0.15 s).

## Compatibility notes

Everything below is cphalcon's behaviour, reproduced on purpose and pinned by
the parity tests.

* **Operator precedence follows the grammar, not SQL.** From loosest to
  tightest: `AGAINST`; `BETWEEN`, `NOT BETWEEN`; comparisons and `@@ @> <@ &&`;
  `AND`, `OR`; `LIKE`, `ILIKE`; `& | ^`; `* / %`; `+ - ||`; `IS`; `IN`;
  `NOT`, `~`; the JSON operators. So `a = 1 AND b > 0` is `(a = (1 AND b)) > 0`,
  `a + b * c` is `(a + b) * c` and `a BETWEEN 1 AND 2` is `a BETWEEN (1 AND 2)`.
* **`NOT LIKE`, `NOT ILIKE` and `NOT IN` take `NOT`'s level**, which is high
  and right-associative: `a + b NOT LIKE c` is `a + (b NOT LIKE c)`.
* **Unary minus has binary minus's level**: `-a * b` is `(-a) * b`.
* **`NOT BETWEEN` is one token only with exactly one space**; `NOT  BETWEEN` is
  a syntax error.
* **String literals are unescaped** (`\n`, `\r`, `\t`, `\\`, `\'`, `\"`); any
  other escape keeps its backslash. Bracketed identifiers (`[a b]`) are kept
  verbatim, a leading backslash of a name is dropped, and `:` may appear inside
  names (`x::int`). Numbers stay strings.
* **A NUL byte ends the statement**, as it ends a C string. Error messages
  quote the statement up to the NUL but report its full length.
* **Syntax errors name the last token scanned** and quote the input after it,
  so an error on the statement's last byte reads as `Syntax error, unexpected
  EOF`. Token names come from cphalcon's table: `%%` for `%`, `NOT EQUALS` for
  `!=` / `<>`, `HEXAINTEGER` for hexadecimal numbers, `UNKNOWN` for `TRUE`,
  `FALSE`, `{placeholders}`, `NOT BETWEEN`, `~` and `^`. The value is shown for
  value tokens only, even when empty or `0`.
* **Scanning errors** quote what follows the offending byte, cut to 16 bytes
  when more than 16 bytes followed the previous token. When the very first
  token fails, a long message is cut short exactly as cphalcon's fixed-size
  buffer cuts it.
* **A lone `:` is reported as `Scanner: Unknown opcode 58`.**
* **Nesting fails closed** with "Parsing failed, the PHQL statement is nested
  too deeply" exactly where cphalcon's 100-entry Lemon stack overflows (for
  example 96 nested parentheses in a column, 31 nested subqueries, or 33
  chained `NOT LIKE`).
* **A statement without tokens returns `null`**, not an error.

## Development

```bash
composer install
composer test-unit     # PHPUnit
composer analyze       # PHPStan, level max
composer cs            # PHP_CodeSniffer
composer cs-fixer-fix  # php-cs-fixer
```

### Verifying against the C parser

The parity corpus in `tests/fixtures/parity/*.php` lists statements: those of
cphalcon's, phalcon's and this package's test suites
(`tests/harvest-corpus.php` collects them) plus hand-written groups for the
grammar's shape, operator precedence, scanner edge cases, errors, nesting depth
and empty statements.

* `tests/generate-golden.php` runs every statement through ext-phalcon - with
  literals enabled and disabled - and records the outcome in
  `tests/fixtures/parity/*.json`. It refuses to run without the extension, so
  the goldens always come from cphalcon.
* `tests/unit/Parser/FixturesTest.php` replays the goldens against this package.
* `tests/differential.php` compares both implementations live, over the corpus
  and over `--fuzz=N` generated statements (`--seed=S` makes them
  reproducible). It exits quietly without the extension.

```bash
php -d extension=phalcon tests/generate-golden.php   # after changing the corpus
php -d extension=phalcon tests/differential.php --fuzz=50000 --seed=1
```

Deliberate differences would be listed, with the expected outcome and a reason,
in `tests/fixtures/parity/allow-list.php`; there are none.

## Why a hand-written parser

The re2c DFA and the Lemon tables are build artefacts: thousands of lines that
nobody can review, that hide the grammar, and that had drifted from cphalcon
(error messages, scanning errors, string escapes, empty statements, the nesting
limit). Their PHP transliteration was also quadratic on long lists: a
40,000-item `IN` list took a minute. The grammar itself is small, and every
shift/reduce decision Lemon makes comes from a 12-level precedence table. A
precedence-climbing parser over that table, plus a counter that mirrors Lemon's
stack, reproduces the generated parser exactly while staying readable: each
parser method quotes the grammar rules it implements.

<!-- Badges -->
[packagist-version-badge]:   https://img.shields.io/packagist/v/phalcon/phql?include_prereleases&style=flat-square
[packagist-version-link]:    https://packagist.org/packages/phalcon/phql
[packagist-downloads-badge]: https://img.shields.io/packagist/dt/phalcon/phql?style=flat-square
[packagist-downloads-link]:  https://packagist.org/packages/phalcon/phql/stats
[php-version-badge]:          https://img.shields.io/packagist/php-v/phalcon/phql?style=flat-square
[license-badge]:             https://img.shields.io/github/license/phalcon/phql?style=flat-square
[license-link]:              LICENSE

[phalcon-ci-badge]:          https://github.com/phalcon/phql/actions/workflows/main.yml/badge.svg
[phalcon-ci-link]:           https://github.com/phalcon/phql/actions/workflows/main.yml
[sonar-quality-badge]:       https://sonarcloud.io/api/project_badges/measure?project=phalcon_phql&metric=alert_status
[sonar-coverage-badge]:      https://sonarcloud.io/api/project_badges/measure?project=phalcon_phql&metric=coverage
[sonar-link]:                https://sonarcloud.io/summary/new_code?id=phalcon_phql
[pds-skeleton-badge]:        https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square
[pds-skeleton-link]:         https://github.com/php-pds/skeleton

[discord-badge]:             https://img.shields.io/discord/310910488152375297?label=Discord&logo=discord&style=flat-square
[discord-link]:              https://phalcon.io/discord
[contributors-badge]:        https://img.shields.io/github/contributors/phalcon/phql?style=flat-square
[contributors-link]:         https://github.com/phalcon/phql/graphs/contributors
[oc-backers-badge]:          https://img.shields.io/opencollective/backers/phalcon?style=flat-square
[oc-sponsors-badge]:         https://img.shields.io/opencollective/sponsors/phalcon?style=flat-square
[backers-link]:              https://opencollective.com/phalcon
[sponsors-link]:             https://opencollective.com/phalcon
