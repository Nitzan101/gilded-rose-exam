<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

class SulfurasStrategy implements UpdateStrategy
{
    public function update(Item $item): void
    {
        // Legendary item: never changes.
    }
}