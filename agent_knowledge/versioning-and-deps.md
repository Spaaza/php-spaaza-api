# Versioning and Dependencies

## Composer Usage
- `composer install` installs dependencies into `vendor/`.
- `composer update` upgrades dependencies and updates `composer.lock`.

## Version Bumps
- When updating dependencies, also bump the library version in `composer.json`.
- Keep the version aligned with release notes or tags if your workflow uses them.

## Runtime Requirements
- Requires PHP 7.3+ (via Guzzle 7) and `ext-json`.
- Dependencies are managed by Composer; do not edit `vendor/` manually.
