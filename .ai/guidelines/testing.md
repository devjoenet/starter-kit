# Testing

- This project uses Pest as its primary PHP test style.
- Write new feature and unit tests in Pest syntax, not PHPUnit class syntax.
- Use `php artisan make:test {Name}` for feature tests and `php artisan make:test {Name} --unit` for unit tests.
- Do not pass `--phpunit` unless a consuming project explicitly opts out of this starter kit convention.
- Keep Pest test closures typed with `: void`.
- Register shared Pest setup in `tests/Pest.php`.
- Keep generated test defaults in `stubs/test.stub`, `stubs/test.unit.stub`, `stubs/pest.stub`, and `stubs/pest.unit.stub` aligned with Pest conventions.
- Keep PHPStan Pest-aware by including PestStan in `phpstan.neon` when `tests` are part of the PHPStan paths.
- Run the narrowest useful Pest test first, then run `composer test` before finalizing broad testing changes.
