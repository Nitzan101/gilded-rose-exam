<?php

declare(strict_types=1);

namespace GildedRose\Strategies;

use GildedRose\Item;

interface UpdateStrategy
{
    public function update(Item $item): void;
}
