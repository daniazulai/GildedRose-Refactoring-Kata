<?php

namespace GildedRose;

final class ItemQualityUpdater
{
    public static function updateDefaultItem(Item $item): void
    {
        $item->sellIn--;
        $item->quality--;
        if ($item->sellIn < 0) {
            $item->quality--;
        }
    }

    public static function updateAgedBrie(Item $item): void
    {
        $item->sellIn--;
        $item->quality++;
        if ($item->sellIn < 0) {
            $item->quality++;
        }
    }

    public static function updateBackstagePasses(Item $item): void
    {
        $item->sellIn--;
        $item->quality++;
        if ($item->sellIn < 0) {
            $item->quality = 0;
        } else {
            if ($item->sellIn <= 10) {
                $item->quality++;
            }
            if ($item->sellIn <= 5) {
                $item->quality++;
            }
        }
    }

    public static function updateConjuredCake(Item $item): void
    {
        $item->sellIn--;
        $item->quality = $item->quality - 2;
        if ($item->sellIn < 0) {
            $item->quality = $item->quality - 2;
        }
    }
}
