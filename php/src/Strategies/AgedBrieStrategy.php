<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

class AgedBrieStrategy extends AbstractStrategy
{
    private const INCREASE_NORMAL = 1;
    private const INCREASE_AFTER_SELL_DATE = 2;

    protected function applyQualityChange(Item $item): void
    {
        $increase = $item->sellIn <= 0
            ? self::INCREASE_AFTER_SELL_DATE
            : self::INCREASE_NORMAL;

        $this->increaseQuality($item, $increase);
    }
}