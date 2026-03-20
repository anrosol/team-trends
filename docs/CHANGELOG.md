# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed

- Rate limit key in `HasBotProtection` now scoped per class (`static::class`) instead of per method (`__METHOD__`)

## [1.0.0] - 2026-03-13

Initial public release of Team Trends — privacy-first team sentiment surveys using anonymous passphrases.

[Unreleased]: https://github.com/anrosol/team-trends/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/anrosol/team-trends/releases/tag/v1.0.0
