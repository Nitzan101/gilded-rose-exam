# Gilded Rose Refactoring Kata

This repository contains my refactored solution for the Gilded Rose Kata in PHP, including the complete implementation of the new "Conjured" items feature.

## Requirements

- **PHP** >= 8.0
- **[Composer](https://getcomposer.org/)**
- **Git** (recommended)
- **[Xdebug](https://xdebug.org/download)** (only needed for the HTML coverage report)

## Installation

```bash
git clone <repository-url>
cd <repository>/php
composer install
```

## Running the Application

The application simulates the inn's inventory over a number of days and prints each item's
`name, sellIn, quality` for each day.

```bash
# Run the default simulation (2 days)
php fixtures/texttest_fixture.php

# Run for a custom number of days (e.g. 30)
php fixtures/texttest_fixture.php 30
```

Example output:

```
OMGHAI!
-------- day 0 --------
name, sellIn, quality
+5 Dexterity Vest, 10, 20
Aged Brie, 2, 0
...
Conjured Mana Cake, 3, 6
```

## 🏗️ Architecture & Refactoring Strategy

The original codebase consisted of a monolithic `GildedRose` class containing deeply nested, highly complex `if/else` statements. To meet modern Object-Oriented standards and adhere to SOLID principles (specifically the Open/Closed Principle), I completely restructured the core logic using the **Strategy Pattern**.

* **`UpdateStrategy` (Interface):** Defines the standard contract that every strategy must fulfil — a single `update(Item $item)` method.
* **`AbstractStrategy` (Base Class):** Implements the shared logic via a template method (quality bounds, sell-in aging, and the "degrades faster after the sell date" rate calculation), so concrete strategies hold only their own rules.
* **Concrete Strategies:** Isolated classes (e.g., `NormalItemStrategy`, `ConjuredStrategy`) that encapsulate the unique business rules of each specific item type.
* **Strategy Resolver:** A factory/resolver mechanism that evaluates an item's name and dynamically assigns the correct strategy.

**Key Benefits of this Architecture:**
* **Low Complexity:** Eliminated nested conditionals.
* **No Code Duplication:** Shared logic is abstracted, while unique rules are strictly isolated.
* **Extensibility:** Future items can be added by simply creating a new strategy class without modifying existing, tested logic.
* **Clean Code:** Removed magic numbers and utilized descriptive constants and meaningful naming conventions.

### Project Structure

```
src/
├── GildedRose.php              # Orchestrator: loops items and delegates to a strategy
├── Item.php                    # Domain object (unchanged — per kata constraints)
├── StrategyResolver.php        # Maps an Item to its UpdateStrategy
└── Strategies/
    ├── UpdateStrategy.php       # Interface (the contract)
    ├── AbstractStrategy.php     # Shared logic: quality bounds, sellIn aging, rate calc
    ├── NormalItemStrategy.php
    ├── AgedBrieStrategy.php
    ├── SulfurasStrategy.php
    ├── BackstagePassStrategy.php
    └── ConjuredStrategy.php
```

### Adding a New Item Type

1. Create a new strategy in `src/Strategies/` extending `AbstractStrategy`.
2. Add a matching line to `StrategyResolver::resolve()`.

No existing strategy or the orchestrator needs to change (Open/Closed Principle).

## Business Rules

| Item | Rule |
|------|------|
| **Normal** | Quality −1/day; −2 after the sell date; never below 0 |
| **Aged Brie** | Quality +1/day; +2 after the sell date; never above 50 |
| **Sulfuras** | Legendary: never changes quality (80) and never ages |
| **Backstage passes** | +1 normally; +2 when ≤10 days; +3 when ≤5 days; drops to 0 after the concert; never above 50 |
| **Conjured** | Degrades twice as fast as normal: −2/day, −4 after the sell date; never below 0 |

## 🧪 Testing Strategy

To guarantee the safety of the refactoring and the accuracy of the new feature, this repository utilizes a dual testing approach:

1. **Isolated Unit Tests (`GildedRoseTest.php`):** Comprehensive test coverage that isolates and verifies a single behavior/business rule per test. This suite was used to safely map the legacy code and strictly apply **Test-Driven Development (TDD)** for the implementation of the "Conjured" feature.
2. **Golden Master / Approval Tests (`ApprovalTest.php`):**
   A snapshot test that runs the entire system for 30 days. The baseline (`.approved.txt`) was successfully updated and verified after the integration of the "Conjured" items to ensure zero unintended side effects across the broader system.

### How to Run the Tests

To execute the complete test suite (Unit Tests + Approval Tests), run the following command from the project root:

```bash
composer tests
```

To generate an HTML coverage report (output in `build/coverage`, requires Xdebug):

```bash
composer test-coverage
```

## Code Quality Tools

```bash
composer phpstan     # Static analysis (PHPStan)
composer check-cs    # Check coding standard (PSR-12 via Easy Coding Standard)
composer fix-cs      # Auto-fix coding standard issues
```

## Notes / Assumptions

- **Aged Brie after the sell date (assumption):** the specification states quality
  "degrades twice as fast" once the sell date passes, and separately that Aged Brie
  *increases* in quality. The "twice as fast" rule is worded about *degrading*, so the spec
  does not explicitly say how an *increasing* item behaves after its sell date. Following the
  conventional reading of the kata, this solution assumes Aged Brie *increases twice as fast*
  (+2) after the sell date.
- **Conjured after the sell date (interpretation):** the spec states Conjured items "degrade
  twice as fast as normal items," and separately that any item degrades twice as fast after
  the sell date. This solution stacks both rules: Conjured degrades by 2 before the sell date
  and by 4 after it.
- **Item matching:** Conjured and Backstage items are matched by **name prefix**
  (e.g. `Conjured Mana Cake`), since these are categories rather than single products.
