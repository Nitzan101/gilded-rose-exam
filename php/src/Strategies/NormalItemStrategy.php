<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

class NormalItemStrategy extends AbstractStrategy
{
    private const DECREASE_NORMAL = 1;
    private const DECREASE_AFTER_SELL_DATE = 2;

    protected function applyQualityChange(Item $item): void
    {
        $decrease = $item->sellIn <= 0
            ? self::DECREASE_AFTER_SELL_DATE : 
              self::DECREASE_NORMAL;

        $this->decreaseQuality($item, $decrease);
    }
}