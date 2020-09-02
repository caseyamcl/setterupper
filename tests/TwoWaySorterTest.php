<?php
declare(strict_types=1);

namespace SetterUpper;

use PHPUnit\Framework\TestCase;

class TwoWaySorterTest extends TestCase
{
    public function testInstantiate(): void
    {
        $sorter = new TwoWaySorter();
        $this->assertInstanceOf(TwoWaySorter::class, $sorter);
    }

    public function testBasicDependencies(): void
    {
        $sorter = new TwoWaySorter();
        $sorter->add('a', ['b', 'c']);
        $sorter->add('b', ['c']);
        $sorter->add('c');
        $this->assertSame(['c', 'b', 'a'], $sorter->sort());
    }

    public function testTwoWayDependency(): void
    {
        $sorter = new TwoWaySorter();
        $sorter->add('a', ['b', 'c', 'd'], ['e']);
        $sorter->add('b', ['c'], ['d']);
        $sorter->add('c');
        $sorter->add('d');
        $sorter->add('e');

        $this->assertSame(['c', 'b', 'd', 'a', 'e'], $sorter->sort());
    }

    public function testCount(): void
    {
        $sorter = new TwoWaySorter();
        $sorter->add('a', ['b']);
        $sorter->add('b');
        $this->assertSame(2, $sorter->count());
    }
}
