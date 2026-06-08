<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

class ConjuredStrategy extends AbstractStrategy
{
    private const DECREASE_NORMAL = 2;
    private const DECREASE_AFTER_SELL_DATE = 4;

    protected function applyQualityChange(Item $item): void
    {
        $amount = $this->calculateAmount($item, self::DECREASE_NORMAL, self::DECREASE_AFTER_SELL_DATE);
        $this->decreaseQuality($item, $amount);
    }
}