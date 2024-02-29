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
}
