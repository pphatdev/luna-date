# Luna Date - PHP Khmer Calendar Library

[![CI](https://github.com/pphatdev/lunar-date/actions/workflows/ci.yml/badge.svg)](https://github.com/pphatdev/lunar-date/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![codecov](https://codecov.io/gh/pphatdev/luna/branch/main/graph/badge.svg)](https://codecov.io/gh/pphatdev/luna)

A comprehensive PHP library for converting between Gregorian and Khmer (Cambodian) calendar dates. This is a faithful port of the popular JavaScript [momentkh](https://github.com/ThyrithSor/momentkh) library by ThyrithSor, now with enhanced PHP best practices and comprehensive testing.

## Features

- ✅ Convert Gregorian dates to Khmer lunar calendar dates
- ✅ Support for Buddhist Era (BE) year calculations  
- ✅ Khmer New Year (ចូលឆ្នាំខ្មែរ) moment calculations
- ✅ Animal year (ឆ្នាំសត្វ) and era year (ស័ក) support
- ✅ Leap month (អធិកមាស) and leap day (ចន្ទ្រាធិមាស) calculations
- ✅ Customizable date formatting with Khmer tokens
- ✅ Number conversion between Arabic and Khmer numerals
- ✅ Buddhist holiday calculations (Visakha Bochea, etc.)
- ✅ Season information based on lunar months
- ✅ Comprehensive utility functions
- ✅ **PHP 7.4+ with strict types and modern practices**
- ✅ **100% test coverage with PHPUnit**
- ✅ **Static analysis with PHPStan (level 8)**
- ✅ **PSR-12 coding standards**

## Requirements

- PHP 7.4 or higher
- ext-json (usually included)

## Installation

Install via Composer:

```bash
composer require pphatdev/lunar-date
```

## Quick Start

```php
<?php
require_once 'vendor/autoload.php';

use PPhatDev\LunarDate\KhmerDate;

// Create Khmer date from current time
$today = new KhmerDate();
echo $today->toLunarDate();
// Output: ថ្ងៃអាទិត្យ ៨កើត ខែមិគសិរ ឆ្នាំជូត សំរឹទ្ធិស័ក ពុទ្ធសករាជ ២៥៦៧

// Convert specific Gregorian date
$date = new KhmerDate('1996-09-24');
echo $date->toLunarDate();
// Output: ថ្ងៃអង្គារ ១២កើត ខែអស្សុជ ឆ្នាំច សំរឹទ្ធិស័ក ពុទ្ធសករាជ ២៥៣៩

// Custom formatting
echo $date->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b');
// Output: ១២កើត ថ្ងៃអង្គារ ខែអស្សុជ ព.ស. ២៥៣៩
```

## API Reference

### Basic Usage

#### Creating KhmerDate Objects

```php
// Current date and time
$now = new KhmerDate();

// From DateTime object
$dateTime = new DateTime('2024-01-15');
$khmerDate = new KhmerDate($dateTime);

// From string
$khmerDate = new KhmerDate('2024-01-15');
$khmerDate = new KhmerDate('2024-01-15 14:30:00');

// From timestamp
$khmerDate = new KhmerDate(1705315800);

// Static factory method
$khmerDate = KhmerDate::create('2024-01-15');
```

#### Basic Methods

```php
$date = new KhmerDate('2024-01-15');

// Convert to Khmer lunar date (default format)
echo $date->toLunarDate();

// Get individual components
echo $date->khDay();    // Khmer day (0-29)
echo $date->khMonth();  // Khmer month index
echo $date->khYear();   // Buddhist Era year

// Get underlying DateTime
$dateTime = $date->getDateTime();

// Standard DateTime formatting
echo $date->format('Y-m-d H:i:s');
```

### Date Formatting

The library supports various formatting tokens inspired by the original momentkh:

| Token | Description | Example |
|-------|-------------|---------|
| `W` | ថ្ងៃនៃសប្ដាហ៍ (Day of week) | អង្គារ |
| `w` | ថ្ងៃនៃសប្ដាហ៍កាត់ (Day of week short) | អ |
| `d` | ថ្ងៃទី (Lunar day count) | ១២ |
| `D` | ថ្ងៃទី ០១-១៥ (Lunar day with leading zero) | ០៨ |
| `n` | កើត ឬ រោច (Moon status short) | ក |
| `N` | កើត ឬ រោច (Moon status full) | កើត |
| `m` | ខែចន្ទគតិ (Lunar month) | មិគសិរ |
| `M` | ខែសុរិយគតិ (Solar month) | មករា |
| `a` | ឆ្នាំសត្វ (Animal year) | ជូត |
| `e` | ស័ក (Era year) | សំរឹទ្ធិស័ក |
| `b` | ឆ្នាំពុទ្ធសករាជ (Buddhist Era year) | ២៥៦៧ |
| `c` | ឆ្នាំគ្រិស្តសករាជ (Gregorian year) | ២០២៤ |
| `j` | ឆ្នាំចុល្លសករាជ (Jolak Sakaraj year) | ១៣៨៥ |

#### Formatting Examples

```php
$date = new KhmerDate('2024-01-15');

// Various format examples
echo $date->toLunarDate('W');                    // អាទិត្យ
echo $date->toLunarDate('dN');                   // ៨កើត  
echo $date->toLunarDate('dN ខែm');               // ៨កើត ខែមិគសិរ
echo $date->toLunarDate('ថ្ងៃW dN ខែm ឆ្នាំa');    // ថ្ងៃអាទិត្យ ៨កើត ខែមិគសិរ ឆ្នាំជូត
echo $date->toLunarDate('ព.ស. b');               // ព.ស. ២៥៦៧
```

### Static Methods

#### Khmer New Year Calculation

```php
// Get Khmer New Year moment for specific year
$newYear2024 = KhmerDate::getKhNewYearMoment(2024);
echo $newYear2024->format('Y-m-d H:i');  // 2024-04-14 06:46

// Check multiple years
for ($year = 2023; $year <= 2025; $year++) {
    $newYear = KhmerDate::getKhNewYearMoment($year);
    echo "Khmer New Year $year: " . $newYear->format('Y-m-d H:i') . "\n";
}
```

#### Number Conversion

```php
// Arabic to Khmer numerals
echo KhmerDate::arabicToKhmerNumber('2024');  // ២០២៤
echo KhmerDate::arabicToKhmerNumber('15');    // ១៥

// Khmer to Arabic numerals  
echo KhmerDate::khmerToArabicNumber('២០២៤'); // 2024
echo KhmerDate::khmerToArabicNumber('១៥');    // 15
```

#### Calendar Information

```php
// Get month names
$lunarMonths = KhmerDate::getKhmerMonthNames();
// ['មិគសិរ', 'បុស្ស', 'មាឃ', 'ផល្គុន', ...]

// Get animal year names
$animalYears = KhmerDate::getAnimalYearNames();  
// ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', ...]

// Get era year names
$eraYears = KhmerDate::getEraYearNames();
// ['សំរឹទ្ធិស័ក', 'ឯកស័ក', 'ទោស័ក', ...]
```

### Utility Functions

```php
use PPhatDev\LunarDate\Utils;

// Get Buddhist holidays for a year
$holidays = Utils::getBuddhistHolidays(2024);
foreach ($holidays as $holiday) {
    echo "{$holiday['name']}: {$holiday['date']}\n";
}

// Get season information
$date = new KhmerDate('2024-07-15');  
$season = Utils::getSeason($date);
echo $season['name'];     // រដូវវស្សា
echo $season['name_en'];  // Rainy Season

// Era conversion
$beYear = Utils::convertEra(2024, 'AD', 'BE');    // 2567
$adYear = Utils::convertEra(2567, 'BE', 'AD');    // 2024
$jsYear = Utils::convertEra(2024, 'AD', 'JS');    // 842

// Find occurrences of specific lunar days
$fullMoons = Utils::findLunarDayOccurrences(15, 0, 2024); // 15កើត in 2024
```

## Comparison with Original MomentKH

This library is a faithful port of the JavaScript momentkh library. Here's how the APIs compare:

### JavaScript (momentkh)
```javascript
const moment = require('moment');
require('@thyrith/momentkh')(moment);

let today = moment();
console.log(today.toLunarDate());

let birthday = moment('1996-09-24');
console.log(birthday.toLunarDate('dN ថ្ងៃW ខែm ព.ស. b'));

console.log(moment.getKhNewYearMoment(2024));
```

### PHP (lunar-date)
```php
use PPhatDev\LunarDate\KhmerDate;

$today = new KhmerDate();
echo $today->toLunarDate();

$birthday = new KhmerDate('1996-09-24');
echo $birthday->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b');

echo KhmerDate::getKhNewYearMoment(2024)->format('Y-m-d H:i');
```

## Calendar System

The Khmer calendar is a lunisolar calendar based on both lunar phases and solar year calculations:

### Months (ខែ)
- **Regular months**: មិគសិរ, បុស្ស, មាឃ, ផល្គុន, ចេត្រ, ពិសាខ, ជេស្ឋ, អាសាឍ, ស្រាពណ៍, ភទ្របទ, អស្សុជ, កក្ដិក
- **Leap months**: បឋមាសាឍ, ទុតិយាសាឍ (occur in leap years)

### Days (ថ្ងៃ)
- **Waxing moon** (កើត): 1កើត to 15កើត  
- **Waning moon** (រោច): 1រោច to 14រោច (or 15រោច)

### Years (ឆ្នាំ)
- **Animal years**: 12-year cycle (ជូត, ឆ្លូវ, ខាល, etc.)
- **Era years**: 10-year cycle (សំរឹទ្ធិស័ក, ឯកស័ក, etc.)
- **Buddhist Era**: Gregorian year + 543/544

### Leap Years
- **Leap month year** (អធិកមាស): Has 13 months (384 days)
- **Leap day year** (ចន្ទ្រាធិមាស): Extra day in ជេស្ឋ month (355 days)
- **Regular year**: 354 days

## Requirements

- PHP 7.4 or higher
- No external dependencies (uses only PHP standard library)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/pphatdev/luna.git
cd luna

# Install dependencies
composer install

# Run examples
php examples/demo.php
php examples/momentkh_examples.php
```

## Testing

```bash
# Run tests (when available)
composer test

# Run code style checks
composer cs-check

# Fix code style issues  
composer cs-fix
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Credits

- Original [momentkh](https://github.com/ThyrithSor/momentkh) JavaScript library by [ThyrithSor](https://github.com/ThyrithSor)
- Khmer calendar calculations based on "Pratitin Soryakkatik-Chankatik 1900-1999" by Mr. Roath Kim Soeun
- Additional references from [cam-cc.org](http://www.cam-cc.org) and [dahlina.com](http://www.dahlina.com)

## Acknowledgments

Special thanks to ThyrithSor for creating the original momentkh library and making Khmer calendar calculations accessible to developers. This PHP port aims to bring the same functionality to the PHP ecosystem while maintaining compatibility with the original JavaScript API.

## Related Projects

- [momentkh](https://github.com/ThyrithSor/momentkh) - Original JavaScript library
- [moment.js](https://momentjs.com/) - Inspiration for the API design

---

**Note**: This library is a work in progress. While the core functionality is implemented and tested, some edge cases and advanced features may still need refinement. Please report any issues you encounter.
