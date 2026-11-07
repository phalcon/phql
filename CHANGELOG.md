# Changelog

All notable changes are documented here. The format is based on [Keep a Changelog][keep_a_changelog] and this project adheres to [Semantic Versioning][semantic_versioning].

## [Unreleased](https://github.com/phalcon/phql/releases/tag/vx.x.x) (2026-xx-xx)

### Changed

- The scanner and the parser are written by hand instead of being transliterated from cphalcon's re2c scanner and Lemon parser; `resources/files/parser.php`, the global `phql_*` functions and the `c/` sources are gone.
- `Phalcon\Phql\Parser::parse()` returns `?array`: `null` for a statement without any token (empty, blank, or starting with a NUL byte), as cphalcon does.
- `Phalcon\Phql\Scanner\Scanner` was rewritten: `new Scanner(string $phql)`, `next(): Token` and `cursor(): int` replace the `State`-based `scanForToken()` / `getToken()`.
- `Phalcon\Phql\Scanner\Token` holds an `int` opcode (an `Opcode` value or `Token::EOF` / `Token::SCAN_ERROR`) and a value; its `Opcode` type and `$length` property are gone.
- `Phalcon\Phql\Tokens` is `final`, and its `$names` table became `Tokens::name()`.
- `Phalcon\Phql\Scanner\Opcode` is unchanged.
- `ext-mbstring` is no longer required: the scanner works on bytes.
- Parsing is linear in the statement's length: a 40,000-item `IN` list parses in about 0.15 s instead of a minute.

### Removed

- `Phalcon\Phql\Parser\Status`, `Phalcon\Phql\Scanner\State` and `Phalcon\Phql\Scanner\ScannerStatus`.

### Added

- Parity tests against ext-phalcon: a statement corpus with goldens recorded from cphalcon (`tests/generate-golden.php`), `FixturesTest`, and `tests/differential.php` with a seeded fuzzer, run in CI.

### Fixed

Parsing now matches cphalcon exactly. Previously:

- an empty statement threw "PHQL statement cannot be NULL" and a blank one "Syntax error, unexpected EOF", where cphalcon returns no result;
- syntax errors quoted one character instead of the rest of the statement, never showed the token's value, and lacked the statement's length;
- scanning errors did not skip the offending byte, cut the excerpt at the wrong length, and counted characters instead of bytes;
- string literals kept their escape sequences (cphalcon resolves `\n`, `\r`, `\t`, `\\`, `\'`, `\"`);
- deeply nested statements were accepted instead of failing with "Parsing failed, the PHQL statement is nested too deeply";
- parsing time grew quadratically with the length of the statement.

[keep_a_changelog]: https://keepachangelog.com/en/1.1.0/
[semantic_versioning]: https://semver.org/spec/v2.0.0.html
