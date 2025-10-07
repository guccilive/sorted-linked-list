<?php

declare(strict_types=1);

namespace Guccilive\SortedLinkedList\Tests;

use Guccilive\SortedLinkedList\Exception\TypeMismatchException;
use Guccilive\SortedLinkedList\SortedLinkedList;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class SortedLinkedListTest extends TestCase
{
    /** @var SortedLinkedList<int|string> */
    private SortedLinkedList $list;

    protected function setUp(): void
    {
        $this->list = new SortedLinkedList();
    }

    public function testNewListIsEmpty(): void
    {
        $this->assertTrue($this->list->isEmpty());
        $this->assertCount(0, $this->list);
    }

    public function testInsertSingleInteger(): void
    {
        $this->list->insert(42);

        $this->assertFalse($this->list->isEmpty());
        $this->assertCount(1, $this->list);
        $this->assertEquals([42], $this->list->toArray());
    }

    public function testInsertMultipleIntegersInOrder(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);
        $this->list->insert(30);

        $this->assertEquals([10, 20, 30], $this->list->toArray());
        $this->assertCount(3, $this->list);
    }

    public function testInsertMultipleIntegersOutOfOrder(): void
    {
        $this->list->insert(30);
        $this->list->insert(10);
        $this->list->insert(20);

        $this->assertEquals([10, 20, 30], $this->list->toArray());
    }

    public function testInsertWithDuplicates(): void
    {
        $this->list->insert(20);
        $this->list->insert(10);
        $this->list->insert(20);
        $this->list->insert(30);
        $this->list->insert(20);

        $this->assertEquals([10, 20, 20, 20, 30], $this->list->toArray());
        $this->assertCount(5, $this->list);
    }

    public function testInsertStringsInAlphabeticalOrder(): void
    {
        $this->list->insert('delta');
        $this->list->insert('alpha');
        $this->list->insert('charlie');
        $this->list->insert('bravo');

        $this->assertEquals(['alpha', 'bravo', 'charlie', 'delta'], $this->list->toArray());
    }

    public function testInsertNegativeNumbers(): void
    {
        $this->list->insert(-5);
        $this->list->insert(10);
        $this->list->insert(-15);
        $this->list->insert(0);

        $this->assertEquals([-15, -5, 0, 10], $this->list->toArray());
    }

    public function testContainsFindsExistingValue(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);
        $this->list->insert(30);

        $this->assertTrue($this->list->contains(20));
        $this->assertTrue($this->list->contains(10));
        $this->assertTrue($this->list->contains(30));
    }

    public function testContainsReturnsFalseForMissingValue(): void
    {
        $this->list->insert(10);
        $this->list->insert(30);

        $this->assertFalse($this->list->contains(20));
        $this->assertFalse($this->list->contains(40));
    }

    public function testContainsOnEmptyList(): void
    {
        $this->assertFalse($this->list->contains(10));
    }

    public function testRemoveExistingValue(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);
        $this->list->insert(30);

        $result = $this->list->remove(20);

        $this->assertTrue($result);
        $this->assertEquals([10, 30], $this->list->toArray());
        $this->assertCount(2, $this->list);
    }

    public function testRemoveFirstOccurrenceOnly(): void
    {
        $this->list->insert(20);
        $this->list->insert(20);
        $this->list->insert(20);

        $this->list->remove(20);

        $this->assertEquals([20, 20], $this->list->toArray());
    }

    public function testRemoveNonExistentValue(): void
    {
        $this->list->insert(10);
        $this->list->insert(30);

        $result = $this->list->remove(20);

        $this->assertFalse($result);
        $this->assertCount(2, $this->list);
    }

    public function testRemoveFromEmptyList(): void
    {
        $result = $this->list->remove(10);

        $this->assertFalse($result);
    }

    public function testRemoveHeadNode(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);

        $this->list->remove(10);

        $this->assertEquals([20], $this->list->toArray());
    }

    public function testGetValueAtIndex(): void
    {
        $this->list->insert(30);
        $this->list->insert(10);
        $this->list->insert(20);

        $this->assertEquals(10, $this->list->get(0));
        $this->assertEquals(20, $this->list->get(1));
        $this->assertEquals(30, $this->list->get(2));
    }

    public function testGetThrowsExceptionForNegativeIndex(): void
    {
        $this->list->insert(10);

        $this->expectException(OutOfBoundsException::class);
        $this->list->get(-1);
    }

    public function testGetThrowsExceptionForIndexTooLarge(): void
    {
        $this->list->insert(10);

        $this->expectException(OutOfBoundsException::class);
        $this->list->get(5);
    }

    public function testClearEmptiesList(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);
        $this->list->insert(30);

        $this->list->clear();

        $this->assertTrue($this->list->isEmpty());
        $this->assertCount(0, $this->list);
        $this->assertEquals([], $this->list->toArray());
    }

    public function testClearAllowsNewTypeAfterClear(): void
    {
        $this->list->insert(10);
        $this->list->clear();
        $this->list->insert('string');

        $this->assertEquals(['string'], $this->list->toArray());
    }

    public function testIteratorWithForeach(): void
    {
        $this->list->insert(30);
        $this->list->insert(10);
        $this->list->insert(20);

        $result = [];
        foreach ($this->list as $value) {
            $result[] = $value;
        }

        $this->assertEquals([10, 20, 30], $result);
    }

    public function testCountableInterface(): void
    {
        $this->assertCount(0, $this->list);

        $this->list->insert(10);
        $this->assertCount(1, $this->list);

        $this->list->insert(20);
        $this->assertCount(2, $this->list);

        $this->list->remove(10);
        $this->assertCount(1, $this->list);
    }

    public function testTypeMismatchThrowsException(): void
    {
        $this->list->insert(10);

        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage('List contains int values, but received string');
        
        $this->list->insert('string');
    }

    public function testTypeMismatchWithStringThenInt(): void
    {
        $this->list->insert('alpha');

        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage('List contains string values, but received int');
        
        $this->list->insert(42);
    }

    public function testLargeDataset(): void
    {
        // Insert 100 random integers
        $values = [];
        for ($i = 0; $i < 100; $i++) {
            $value = rand(1, 1000);
            $values[] = $value;
            $this->list->insert($value);
        }

        // Verify count
        $this->assertCount(100, $this->list);

        // Verify sorting
        $sorted = $this->list->toArray();
        $expected = $values;
        sort($expected);
        $this->assertEquals($expected, $sorted);
    }

    public function testRemoveAllElements(): void
    {
        $this->list->insert(10);
        $this->list->insert(20);

        $this->list->remove(10);
        $this->list->remove(20);

        $this->assertTrue($this->list->isEmpty());
        $this->assertCount(0, $this->list);
    }

    public function testInsertAtHead(): void
    {
        $this->list->insert(20);
        $this->list->insert(30);
        $this->list->insert(10); // Should become head

        $this->assertEquals(10, $this->list->get(0));
    }

    public function testEdgeCaseZeroValue(): void
    {
        $this->list->insert(0);
        $this->list->insert(-5);
        $this->list->insert(5);

        $this->assertEquals([-5, 0, 5], $this->list->toArray());
    }

    public function testEmptyStringInsertion(): void
    {
        $this->list->insert('');
        $this->list->insert('a');

        $this->assertEquals(['', 'a'], $this->list->toArray());
    }
}
