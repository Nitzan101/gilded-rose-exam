<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    // Normal

    public function testNormalItemDecreasesQualityByOneEachDay(): void
    {
        $item = $this->updateItem('item1', 10, 20);
        
        $this->assertSame(9, $item->sellIn);
        $this->assertSame(19, $item->quality);    
    }

    public function testNormalItemDegradesTwiceAsFastAfterSellDate(): void
    {
        $item = $this->updateItem('item1', 0, 20);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(18, $item->quality);  
    }

    public function testNormalItemQualityNeverGoesNegative(): void
    {
        $item = $this->updateItem('item1', 3, 0);
        
        $this->assertSame(2, $item->sellIn);
        $this->assertSame(0, $item->quality);  
    }

    public function testNormalItemQualityNeverGoesNegativeWhenDegradingTwiceAsFast(): void
    {
        $item = $this->updateItem('item1', 0, 1);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);  
    }

    // Aged Brie

    public function testAgedBrieIncreasesQualityByOneEachDay(): void
    {
        $item = $this->updateItem('Aged Brie', 10, 20);

        $this->assertSame(9, $item->sellIn);
        $this->assertSame(21, $item->quality);  
    }

    public function testAgedBrieQualityDoesNotExceed50(): void
    {
        $maxQuality = 50;
        $item = $this->updateItem('Aged Brie', 10, $maxQuality);
        
        $this->assertSame(9, $item->sellIn);
        $this->assertSame($maxQuality, $item->quality);  
    }

    public function testAgedBrieIncreasesTwiceAsFastAfterSellDate(): void
    {
        $item = $this->updateItem('Aged Brie', 0, 20);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(22, $item->quality);  
    }

    public function testAgedBrieQualityDoesNotExceed50WhenIncreasingTwiceAsFast(): void
    {
        $maxQuality = 50;
        $item = $this->updateItem('Aged Brie', 0, 49);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame($maxQuality, $item->quality);  
    }

    // Sulfuras

    public function testSulfurasNeverChangesQualityOrSellIn(): void
    {
        $sulfurasQuality = 80;
        $item = $this->updateItem('Sulfuras, Hand of Ragnaros', 10, $sulfurasQuality);
        
        $this->assertSame(10, $item->sellIn);
        $this->assertSame($sulfurasQuality, $item->quality);  
    }

    public function testSulfurasNeverChangesQualityOrSellInWithNegativeSellIn(): void
    {
        $sulfurasQuality = 80;
        $item = $this->updateItem('Sulfuras, Hand of Ragnaros', -1, $sulfurasQuality);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame($sulfurasQuality, $item->quality);  
    }

    // Backstage

    public function testBackstagePassIncreasesQualityByOneAtExactlyElevenDays(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 11, 20);
        
        $this->assertSame(10, $item->sellIn);
        $this->assertSame(21, $item->quality);  
    }

    public function testBackstagePassIncreasesQualityByTwoAtExactlyTenDays(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 10, 20);
        
        $this->assertSame(9, $item->sellIn);
        $this->assertSame(22, $item->quality);  
    }

    public function testBackstagePassIncreasesQualityByTwoAtExactlySixDays(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 6, 20);
        
        $this->assertSame(5, $item->sellIn);
        $this->assertSame(22, $item->quality);  
    }

    public function testBackstagePassQualityDoesNotExceed50InPlusTwoZone(): void
    {
        $maxQuality = 50;
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 10, 49);
        
        $this->assertSame(9, $item->sellIn);
        $this->assertSame($maxQuality, $item->quality);
    }

    public function testBackstagePassIncreasesQualityByThreeAtExactlyOneDay(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 1, 20);

        $this->assertSame(0, $item->sellIn);
        $this->assertSame(23, $item->quality);  
    }

    public function testBackstagePassIncreasesQualityByThreeAtExactlyFiveDays(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 5, 20);

        $this->assertSame(4, $item->sellIn);
        $this->assertSame(23, $item->quality); 
    }

    public function testBackstagePassQualityDropsToZeroAfterConcert(): void
    {
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 0, 20);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);  
    }

    public function testBackstageQualityDoesNotExceed50WhenIncreasingByThree(): void
    {
        $maxQuality = 50;
        $item = $this->updateItem('Backstage passes to a TAFKAL80ETC concert', 1, 48);
        
        $this->assertSame(0, $item->sellIn);
        $this->assertSame($maxQuality, $item->quality);  
    }

    // Conjured

    public function testConjuredDecreasesQualityByTwoEachDay(): void
    {
        $item = $this->updateItem('Conjured', 10, 20);
        
        $this->assertSame(9, $item->sellIn);
        $this->assertSame(18, $item->quality);    
    }

    public function testConjuredDegradesTwiceAsFastAfterSellDate(): void
    {
        $item = $this->updateItem('Conjured', 0, 20);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(16, $item->quality);  
    }

    public function testConjuredQualityNeverGoesNegative(): void
    {
        $item = $this->updateItem('Conjured', 3, 1);
        
        $this->assertSame(2, $item->sellIn);
        $this->assertSame(0, $item->quality);  
    }

    public function testConjuredQualityNeverGoesNegativeWhenDegradingTwiceAsFast(): void
    {
        $item = $this->updateItem('Conjured', 0, 3);
        
        $this->assertSame(-1, $item->sellIn);
        $this->assertSame(0, $item->quality);  
    }

    // private functions

    private function updateItem(string $name, int $sellIn, int $quality): Item
    {
        $item = new Item($name, $sellIn, $quality);
        (new GildedRose([$item]))->updateQuality();
        return $item;
    }
}