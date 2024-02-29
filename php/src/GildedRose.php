<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    const MAX_QUALITY = 50;
    const MIN_QUALITY = 0;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateItemQuality($item);
        }
    }

    private function updateItemQuality(Item $item): void
    {
        switch ($item->name) {
            case ItemType::AGED_BRIE:
                ItemQualityUpdater::updateAgedBrie($item);
                break;
            case ItemType::BACKSTAGE_PASSES:
                ItemQualityUpdater::updateBackstagePasses($item);
                break;
            case ItemType::CONJURED_CAKE:
                ItemQualityUpdater::updateConjuredCake($item);
                break;
            case ItemType::SULFURAS:
                break;
            default:
                ItemQualityUpdater::updateDefaultItem($item);
                break;
        }

        $this->checkAndUpdateMinMaxQuality($item);
    }

    private function checkAndUpdateMinMaxQuality(Item $item): void
    {
        if ($item->quality < self::MIN_QUALITY) {
            $item->quality = self::MIN_QUALITY;
        }

        if ($item->name !== ItemType::SULFURAS) {
            if ($item->quality > self::MAX_QUALITY) {
                $item->quality = self::MAX_QUALITY;
            }
        }
    }
}
