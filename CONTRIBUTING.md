# Contributing to Lunar Date

Thank you for your interest in contributing to Lunar Date! This guide will help you get started.

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/pphatdev/lunar-date.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feature/your-feature-name`

## Development Workflow

### Code Standards

We follow PSR-12 coding standards and use modern PHP practices:

- PHP 8.0+ with strict types (`declare(strict_types=1);`)
- Type hints for all method parameters and return values
- Comprehensive PHPDoc documentation
- Final classes where appropriate
- Proper exception handling

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test:coverage

# Run specific test class
./vendor/bin/phpunit tests/KhmerCalculatorTest.php
```

### Code Quality Checks

```bash
# Check code style
composer cs-check

# Fix code style automatically
composer cs-fix

# Run static analysis
composer stan

# Run all quality checks
composer quality
```

### Using Make (optional)

If you have `make` available:

```bash
make help        # Show available commands
make quality     # Run all quality checks
make ci         # Run CI checks locally
```

## Testing Guidelines

- All public methods must have tests
- Test edge cases and error conditions
- Use descriptive test method names
- Include docblocks for complex test scenarios
- Aim for 100% code coverage

### Test Structure

```php
public function testMethodName(): void
{
    // Arrange - Set up test data
    $input = 'test-input';
    
    // Act - Execute the method being tested
    $result = $this->subject->methodName($input);
    
    // Assert - Verify the results
    $this->assertEquals('expected-result', $result);
}
```

## Documentation

- Update README.md for new features
- Add PHPDoc comments for all public methods
- Include usage examples for complex features
- Keep examples up to date

## Submitting Changes

1. Ensure all tests pass: `composer quality`
2. Add tests for new functionality
3. Update documentation if needed
4. Commit with descriptive messages
5. Push to your fork
6. Create a Pull Request

### Commit Message Format

```
type: brief description

Longer description if needed

- List any breaking changes
- Reference related issues (#123)
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

## Code Review Process

1. All changes require review
2. CI checks must pass
3. Maintain or improve code coverage
4. Address reviewer feedback
5. Squash commits if requested

## Getting Help

- Open an issue for bugs or feature requests
- Join discussions in existing issues
- Ask questions in Pull Request comments

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
