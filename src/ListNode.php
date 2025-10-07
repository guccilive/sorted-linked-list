<?php

declare(strict_types=1);

namespace Guccilive\SortedLinkedList;

/**
 * @internal This class is not part of the public API
 * @template T of int|string
 */
final class ListNode
{
    /**
     * @var ListNode<T>|null
     */
    public ?ListNode $next = null;

    /**
     * @param T $value The value to store in this node
     */
    public function __construct(
        public readonly int|string $value
    ) {
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    /**
     * @return T
     */
    public function getValue(): int|string
    {
        return $this->value;
    }
}
