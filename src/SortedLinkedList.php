<?php

declare(strict_types=1);

namespace Guccilive\SortedLinkedList;

use Guccilive\SortedLinkedList\Exception\TypeMismatchException;
use OutOfBoundsException;
use Traversable;

/**
 * @template T of int|string
 * @implements SortedLinkedListInterface<T>
 */
final class SortedLinkedList implements SortedLinkedListInterface
{
    /**
     * @var ListNode<T>|null
     */
    private ?ListNode $head = null;

    /**
     * @var int<0, max>
     */
    private int $count = 0;

    private ?string $valueType = null;

    /**
     * @inheritDoc
     */
    public function insert(int|string $value): void
    {
        $this->enforceTypeConsistency($value);

        $newNode = new ListNode($value);

        if ($this->head === null) {
            $this->head = $newNode;
            $this->count++;
            return;
        }

        if ($value < $this->head->value) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            $this->count++;
            return;
        }

        $current = $this->head;
        while ($current->next !== null && $current->next->value < $value) {
            $current = $current->next;
        }

        $newNode->next = $current->next;
        $current->next = $newNode;
        $this->count++;
    }

    /**
     * @inheritDoc
     */
    public function contains(int|string $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        $current = $this->head;

        while ($current !== null && $current->value <= $value) {
            if ($current->value === $value) {
                return true;
            }
            $current = $current->next;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(int|string $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        if ($this->head->value === $value) {
            $this->head = $this->head->next;
            $this->count = max(0, $this->count - 1);

            if ($this->head === null) {
                $this->valueType = null;
            }

            return true;
        }

        $current = $this->head;
        while ($current->next !== null) {
            if ($current->next->value > $value) {
                return false;
            }

            if ($current->next->value === $value) {
                $current->next = $current->next->next;
                $this->count = max(0, $this->count - 1);
                return true;
            }

            $current = $current->next;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function get(int $index): int|string
    {
        if ($index < 0 || $index >= $this->count) {
            throw new OutOfBoundsException(
                sprintf('Index %d is out of bounds for list of size %d', $index, $this->count)
            );
        }

        $current = $this->head;
        $currentIndex = 0;

        while ($current !== null) {
            if ($currentIndex === $index) {
                return $current->value;
            }
            $current = $current->next;
            $currentIndex++;
        }

        throw new OutOfBoundsException('Unexpected error during traversal');
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->head = null;
        $this->count = 0;
        $this->valueType = null;
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * @inheritDoc
     * @return int<0, max>
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     * @return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        $current = $this->head;

        while ($current !== null) {
            yield $current->value;
            $current = $current->next;
        }
    }

    /**
     * @param int|string $value The value to validate
     * @throws TypeMismatchException
     */
    private function enforceTypeConsistency(int|string $value): void
    {
        $currentType = get_debug_type($value);

        if ($currentType !== 'int' && $currentType !== 'string') {
            throw TypeMismatchException::unsupportedType($value);
        }

        if ($this->valueType === null) {
            $this->valueType = $currentType;
            return;
        }

        if ($this->valueType !== $currentType) {
            throw TypeMismatchException::create($this->valueType, $currentType);
        }
    }
}
