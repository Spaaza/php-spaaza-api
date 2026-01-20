# Repository Guidelines

## Project Structure & Module Organization
- `src/` holds the PHP client implementation, including `Client.php` and `APIException.php` under the `spaaza\\client` namespace.
- `vendor/` is Composer-managed dependencies; do not edit by hand.
- `composer.json` defines package metadata, autoloading, and required PHP extensions.
- `README.md` contains a minimal usage example and dependency notes.

## Build, Test, and Development Commands
- `composer install` installs dependencies into `vendor/`.
- `composer update` updates dependencies; when doing this, also update the version in `composer.json` as noted in `README.md`.
- No build step is required beyond Composer; this is a library package.

## Coding Style & Naming Conventions
- PHP namespaces follow `spaaza\\client` and class names are PascalCase (e.g., `Client`, `APIException`).
- Keep indentation consistent with existing files (4 spaces in PHP; 2 spaces in JSON).
- Prefer short, focused methods and clear request/response naming (e.g., `getRequest`, `postRequest`).

## Testing Guidelines
- No test suite is currently configured in this repository.
- If adding tests, document the framework and add a runnable command (e.g., `vendor/bin/phpunit`).

## Agent Knowledge Notes
- The `agent_knowledge/` directory captures durable, public-safe tips for future agents.
- Do not add confidential, internal, or customer-specific information; this is a public repo.
- Add short, topic-focused Markdown files (e.g., `agent_knowledge/error-handling.md`) and keep notes concise.

## Commit & Pull Request Guidelines
- Commit messages are short, descriptive, and sentence case (e.g., "Updated client parameters...", "Bump guzzlehttp/psr7...").
- For dependency updates, follow the "Bump package from X to Y" pattern.
- PRs should include a brief summary, any API-facing changes, and references to related issues if applicable.

## Security & Configuration Notes
- This client relies on Guzzle 7 and PHP >= 7.3 (see `composer.json`).
- Avoid hardcoding credentials; pass auth or base URLs at runtime as shown in `README.md`.
