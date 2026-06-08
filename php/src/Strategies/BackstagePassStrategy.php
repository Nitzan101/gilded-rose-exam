<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

class BackstagePassStrategy extends AbstractStrategy
{
    private const FINAL_WEEK_DAYS = 10;
    private const FINAL_DAYS = 5;

    private const INCREASE_FAR    = 1;
    private const INCREASE_CLOSE  = 2;
    private const INCREASE_URGENT = 3;

    protected function applyQualityChange(Item $item): void
    {
        if ($item->sellIn <= 0) {
            $item->quality = self::MIN_QUALITY;
            return;
        }

        $this->increaseQuality($item, $this->resolveIncrease($item->sellIn));
    }

    private function resolveIncrease(int $sellIn): int
    {
        return match (true) {
            $sellIn <= self::FINAL_DAYS      => self::INCREASE_URGENT,
            $sellIn <= self::FINAL_WEEK_DAYS => self::INCREASE_CLOSE,
            default                          => self::INCREASE_FAR,
        };
    }
}