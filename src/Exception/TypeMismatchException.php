<?php

declare(strict_types=1);

namespace Guccilive\SortedLinkedList\Exception;

use InvalidArgumentException;

final class TypeMismatchException extends InvalidArgumentException
{
    /**
     * @param string $expectedType The type that the list currently contains
     * @param string $givenType The type that was attempted to be inserted
     */
    public static function create(string $expectedType, string $givenType): self
    {
        return new self(
            sprintf(
                'Type mismatch detected. List contains %s values, but received %s. ' .
                'All values in a sorted list must be of the same type.',
                $expectedType,
                $givenType
            )
        );
    }

    /**
     * @param mixed $value The value with unsupported type
     */
    public static function unsupportedType(mixed $value): self
    {
        $type = get_debug_type($value);
        
        return new self(
            sprintf(
                'Unsupported type: %s. Only int and string types are supported.',
                $type
            )
        );
    }
}
