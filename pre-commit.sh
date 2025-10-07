#!/bin/bash

echo "Running pre-commit checks..."

echo "→ Fixing coding standards..."
composer cs:fix

# Run all checks
echo "→ Running tests, PHPStan, and coding standards..."
composer check

if [ $? -ne 0 ]; then
    echo "❌ Checks failed! Please fix the issues before committing."
    exit 1
fi

echo "✅ All checks passed! Ready to commit."
exit 0
