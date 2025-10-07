# Sorted Linked List

## Development

### Setup

```bash
# Clone the repository
git clone https://github.com/guccilive/sorted-linked-list.git
cd sorted-linked-list

# Install dependencies
composer install
# or
make install
```

### Running Tests

```bash
# Run tests
composer test
# or
make test

# Run tests with coverage (requires Xdebug or PCOV)
composer test:coverage
# or
make coverage
```

### Code Quality

```bash
# Run all checks (tests + phpstan + cs)
composer check
# or
make check

# Run PHPStan
composer phpstan
# or
make phpstan

# Check coding standards
composer cs
# or
make cs

# Fix coding standards
composer cs:fix
# or
make cs-fix
```

### Pre-Commit Hook

The repository includes an optional pre-commit hook that automatically runs quality checks before each commit:

```bash
# The hook is already set up and will:
# 1. Auto-fix coding standards (composer cs:fix)
# 2. Run all checks (composer check)
# 3. Block commit if checks fail

# To bypass the hook (emergency only):
git commit --no-verify -m "your message"

# To run checks manually:
./pre-commit.sh
```

The pre-commit hook ensures code quality by catching issues before they reach the repository.

### Code Coverage

To generate code coverage reports, you need to install a coverage driver.

#### Installing Xdebug (macOS with Homebrew)

```bash
pecl install xdebug
```

#### Installing PCOV (Lightweight alternative)

```bash
pecl install pcov
```

Then add to your `php.ini`:

```ini
extension=pcov.so
pcov.enabled=1
```

Once installed, run:

```bash
composer test:coverage  # HTML report
composer test:coverage-text  # Terminal output
```

- ✅ **Type Safety**: Enforces consistent types (int or string) throughout the list
- ⚡ **Auto-Sorting**: Elements maintain sorted order automatically on insertion
- 🎯 **Optimized Operations**: Early-exit algorithms for efficient searching
- 💾 **Memory Efficient**: Single-linked structure with minimal overhead
- 🔄 **Standard Interfaces**: Implements `Countable` and `IteratorAggregate`
- 📊 **O(1) Count**: Cached counter for instant size retrieval
- 🛡️ **Final Classes**: Prevents inheritance issues and enables JIT optimization

## Installation

```bash
composer require guccilive/sorted-linked-list
```

## Requirements

- PHP >= 8.3.0

## Usage

### Basic Operations

```php
use Guccilive\SortedLinkedList\SortedLinkedList;

// Create a new sorted list
$list = new SortedLinkedList();

// Insert values (automatically sorted)
$list->insert(30);
$list->insert(10);
$list->insert(20);

// Get as array
print_r($list->toArray()); // [10, 20, 30]

// Check if value exists
$list->contains(20); // true
$list->contains(40); // false

// Get by index
$list->get(0); // 10
$list->get(1); // 20

// Count elements
count($list); // 3
$list->isEmpty(); // false

// Remove value
$list->remove(20);
print_r($list->toArray()); // [10, 30]

// Clear all elements
$list->clear();
```

### Type Safety

The list enforces type consistency - once the first element is inserted, all subsequent elements must be of the same type:

```php
$list = new SortedLinkedList();
$list->insert(10);
$list->insert(20);

// This will throw TypeMismatchException
$list->insert('string'); // ❌ Error: List contains int values, but received string
```

### String Support

Works perfectly with strings too:

```php
$list = new SortedLinkedList();
$list->insert('delta');
$list->insert('alpha');
$list->insert('charlie');

print_r($list->toArray()); // ['alpha', 'charlie', 'delta']
```

### Iteration

The list implements `IteratorAggregate`, so you can use it in foreach loops:

```php
$list = new SortedLinkedList();
$list->insert(30);
$list->insert(10);
$list->insert(20);

foreach ($list as $value) {
    echo $value . "\n";
}
// Output:
// 10
// 20
// 30
```

### Duplicates

Duplicate values are allowed:

```php
$list = new SortedLinkedList();
$list->insert(20);
$list->insert(10);
$list->insert(20);

print_r($list->toArray()); // [10, 20, 20]

// Remove only removes first occurrence
$list->remove(20);
print_r($list->toArray()); // [10, 20]
```

## API Reference

### `SortedLinkedList` Class

#### Methods

- `insert(int|string $value): void` - Inserts a value in sorted position
- `contains(int|string $value): bool` - Checks if value exists
- `remove(int|string $value): bool` - Removes first occurrence of value
- `get(int $index): int|string` - Gets value at index
- `toArray(): array` - Returns all values as array
- `clear(): void` - Removes all elements
- `isEmpty(): bool` - Checks if list is empty
- `count(): int` - Returns number of elements
- `getIterator(): Traversable` - Returns iterator for foreach

#### Exceptions

- `TypeMismatchException` - Thrown when inserting incompatible type
- `OutOfBoundsException` - Thrown when accessing invalid index

## Architecture

### Design Decisions

- **Final Classes**: Prevents inheritance complexity and enables PHP's JIT optimization
- **Readonly Properties**: Node values are immutable after creation (PHP 8.1+)
- **Strict Types**: `declare(strict_types=1)` in every file
- **Generics**: PHPStan templates for enhanced type safety
- **Early Exits**: Search algorithms terminate early when target cannot exist
- **Cached Count**: O(1) count operations via internal counter

### Performance

- **Insert**: O(n) - Linear scan to find insertion point, optimized with early exits
- **Search**: O(n) - Linear search with early termination for sorted data
- **Remove**: O(n) - Linear scan with early exit optimization
- **Get by Index**: O(n) - Linear traversal to index
- **Count**: O(1) - Cached counter
- **toArray**: O(n) - Single traversal

### Coding Standards

- Follow PSR-12
- Use strict types
- Add PHPDoc blocks
- Write tests for new features
- Keep complexity under 10

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

**Heritier Mashini**

- GitHub: [@guccilive](https://github.com/guccilive)
- Email: 37352340+guccilive@users.noreply.github.com

## Acknowledgments

Built with modern PHP practices and attention to performance and type safety.
