<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

abstract class AbstractStrategy implements UpdateStrategy
{
    protected const MAX_QUALITY = 50;
    protected const MIN_QUALITY = 0;

    public function update(Item $item): void
    {
        $this->applyQualityChange($item);  
        $item->sellIn--;                   
    }

    abstract protected function applyQualityChange(Item $item): void;

    protected function increaseQuality(Item $item, int $amount): void
    {
        $item->quality = min(self::MAX_QUALITY, $item->quality + $amount);
    }

    protected function decreaseQuality(Item $item, int $amount): void
    {
        $item->quality = max(self::MIN_QUALITY, $item->quality - $amount);
    }
}