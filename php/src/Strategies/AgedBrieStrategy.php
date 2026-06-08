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
        $amount = $this->calculateAmount($item, self::INCREASE_NORMAL, self::INCREASE_AFTER_SELL_DATE);
        $this->increaseQuality($item, $amount);
    }
}