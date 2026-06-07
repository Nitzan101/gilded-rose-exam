<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Strategies\UpdateStrategy;
use GildedRose\Strategies\AgedBrieStrategy;
use GildedRose\Strategies\SulfurasStrategy;
use GildedRose\Strategies\BackstagePassStrategy;
use GildedRose\Strategies\NormalItemStrategy;

class StrategyResolver
{
    public function resolve(Item $item): UpdateStrategy
    {
        return match (true) {
            $item->name === 'Aged Brie'                   => new AgedBrieStrategy(),
            $item->name === 'Sulfuras, Hand of Ragnaros'  => new SulfurasStrategy(),
            str_starts_with($item->name, 'Backstage')     => new BackstagePassStrategy(),
            default                                        => new NormalItemStrategy(),
        };
    }
}