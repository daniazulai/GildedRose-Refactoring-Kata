<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use GildedRose\ItemType;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testDefaultItem(): void
    {
        $items = [new Item(ItemType::ELIXER, 1, 3)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        // At the end of each day our system lowers both values for every item
        $this->assertSame($items[0]->sellIn, 0);
        $this->assertSame($items[0]->quality, 2);
        $gildedRose->updateQuality();
        // Once the sell by date has passed, `Quality` degrades twice as fast
        $this->assertSame($items[0]->sellIn, -1);
        $this->assertSame($items[0]->quality, 0);
        // The `Quality` of an item is never negative
        $gildedRose->updateQuality();
        $this->assertSame($items[0]->sellIn, -2);
        $this->assertSame($items[0]->quality, 0);
    }

    public function testAgedBrie(): void
    {
        $items = [new Item(ItemType::AGED_BRIE, 1, 47)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        // Aged Brie actually increases in `Quality` the older it gets
        $this->assertSame($items[0]->sellIn, 0);
        $this->assertSame($items[0]->quality, 48);
        $gildedRose->updateQuality();
        // Once the sell by date has passed, `Quality` increases twice as fast
        $this->assertSame($items[0]->sellIn, -1);
        $this->assertSame($items[0]->quality, 50);
        // The `Quality` of an item is never more than `50`
        $gildedRose->updateQuality();
        $this->assertSame($items[0]->sellIn, -2);
        $this->assertSame($items[0]->quality, 50);
    }
}
