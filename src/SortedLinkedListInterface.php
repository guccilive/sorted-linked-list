<?php

declare(strict_types=1);

namespace Guccilive\SortedLinkedList;

use Countable;
use IteratorAggregate;

/**
 * @template T of int|string
 * @extends IteratorAggregate<int, T>
 */
interface SortedLinkedListInterface extends Countable, IteratorAggregate
{
    /**
     * @param T $value The value to insert
     * @throws \Guccilive\SortedLinkedList\Exception\TypeMismatchException
     */
    public function insert(int|string $value): void;

    /**
     * @param T $value
     */
    public function contains(int|string $value): bool;

    /**
     * @param T $value
     */
    public function remove(int|string $value): bool;

    /**
     * @return T value at the specified position
     * @throws \OutOfBoundsException
     */
    public function get(int $index): int|string;

    /**
     * @return array<int, T>
     */
    public function toArray(): array;

    public function clear(): void;

    public function isEmpty(): bool;
}
