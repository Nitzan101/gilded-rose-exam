<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\StrategyResolver;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items,
    ) {
    }

    public function updateQuality(): void
    {
        $resolver = new StrategyResolver();
        foreach ($this->items as $item) {
            $resolver->resolve($item)->update($item);
        }
    }
}
