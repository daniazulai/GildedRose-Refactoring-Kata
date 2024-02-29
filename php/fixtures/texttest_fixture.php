<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GildedRose\GildedRose;
use GildedRose\Item;
use GildedRose\ItemType;

echo 'OMGHAI!' . PHP_EOL;

$items = [
    new Item(ItemType::DEXTERITY_VEST, 10, 20),
    new Item(ItemType::AGED_BRIE, 2, 0),
    new Item(ItemType::ELIXER, 5, 7),
    new Item(ItemType::SULFURAS, 0, 80),
    new Item(ItemType::SULFURAS, -1, 80),
    new Item(ItemType::BACKSTAGE_PASSES, 15, 20),
    new Item(ItemType::BACKSTAGE_PASSES, 10, 49),
    new Item(ItemType::BACKSTAGE_PASSES, 5, 49),
    new Item(ItemType::CONJURED_CAKE, 3, 6),
];

$app = new GildedRose($items);

$days = 2;
if ((is_countable($argv) ? count($argv) : 0) > 1) {
    $days = (int) $argv[1];
}

for ($i = 0; $i < $days; $i++) {
    echo "-------- day {$i} --------" . PHP_EOL;
    echo 'name, sellIn, quality' . PHP_EOL;
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->updateQuality();
}
