# Contributing to Team Trends

Thank you for your interest in contributing. This guide covers everything you need to get started.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)
- [Local Development Setup](#local-development-setup)
- [Running Tests](#running-tests)
- [Submitting a Pull Request](#submitting-a-pull-request)
- [Code Style](#code-style)

## Code of Conduct

Be respectful. This project exists to help people build trust - that starts here.

See [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md).

## Reporting Bugs

Open a [GitHub issue](https://github.com/anrosol/team-trends/issues) and include:

- A clear description of the problem
- Steps to reproduce
- Expected vs. actual behavior
- Your environment (OS, Docker version, browser if relevant)

For security vulnerabilities, do **not** open a public issue. See [SECURITY.md](SECURITY.md).

## Suggesting Features

Open a GitHub issue with the `enhancement` label. Describe the problem you're trying to solve, not just the solution - this makes it easier to discuss alternatives.

## Local Development Setup

Team Trends uses [Laravel Sail](https://laravel.com/docs/sail) for local development.

### Prerequisites

- PHP 8.5+
- Composer
- Node.js + npm
- Docker

### Steps

1. Clone the repository:

```bash
git clone https://github.com/anrosol/team-trends.git
cd team-trends
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Copy the environment file and generate an app key:

```bash
cp .env.example .env
php artisan key:generate
```

Update `PASSPHRASE_PEPPER` in `.env` with the value returned by `openssl rand -hex 32`.

4. Start via Sail:

```bash
./vendor/bin/sail up -d
```

5. Run migrations:

```bash
sail artisan migrate
```

5. Start Vite:

```bash
sail npm run dev
```

The application will be available at [http://localhost](http://localhost).

## Running Tests

```bash
sail pest
```

When adding new functionality, please include corresponding tests. Privacy and passphrase-related logic should be treated as especially important to cover.

## Submitting a Pull Request

1. Fork the repository and create a branch from `main`:

```bash
git checkout -b my-feature
```

2. Make your changes.
3. Ensure tests pass: `sail pest`
4. Ensure code is formatted: `sail pint`
5. Push your branch and open a pull request against `main`.

Keep pull requests focused. One concern per PR makes review faster and history cleaner.

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) for PHP formatting (Laravel preset).

Run the formatter before committing:

```bash
sail pint
```

Pint is important - PRs with style violations will not be merged.

---

Created by [Anrosol](https://anrosol.com).