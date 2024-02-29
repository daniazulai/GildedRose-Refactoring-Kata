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

    public function testSulfuras(): void
    {
        $items = [new Item(ItemType::SULFURAS, 5, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        // Sulfuras being a legendary item, never has to be sold or decreases in `Quality`
        // Sulfuras is a legendary item and as such its `Quality` is `80` and it never alters
        $this->assertSame($items[0]->sellIn, 5);
        $this->assertSame($items[0]->quality, 80);
    }

    public function testBackstagePasses(): void
    {
        $items = [new Item(ItemType::BACKSTAGE_PASSES, 12, 30)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        // Backstage passes, like aged brie, increases in `Quality` as its `SellIn` value approaches;
        $this->assertSame($items[0]->sellIn, 11);
        $this->assertSame($items[0]->quality, 31);
        $gildedRose->updateQuality();
        // `Quality` increases by `2` when there are `10` days or less
        $this->assertSame($items[0]->sellIn, 10);
        $this->assertSame($items[0]->quality, 33);
        for ($i = 0; $i < 5; $i++) {
            $gildedRose->updateQuality();
        }
        // Skipped 5 days
        $this->assertSame($items[0]->sellIn, 5);
        $this->assertSame($items[0]->quality, 44);
        $gildedRose->updateQuality();
        //  and by `3` when there are `5` days or less but
        $this->assertSame($items[0]->sellIn, 4);
        $this->assertSame($items[0]->quality, 47);
        for ($i = 0; $i < 4; $i++) {
            $gildedRose->updateQuality();
        }
        // Skipped 4 days
        $this->assertSame($items[0]->sellIn, 0);
        $this->assertSame($items[0]->quality, 50);
        $gildedRose->updateQuality();
        // `Quality` drops to `0` after the concert
        $this->assertSame($items[0]->sellIn, -1);
        $this->assertSame($items[0]->quality, 0);
    }
}
