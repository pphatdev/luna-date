# Lunar Date - PHP Khmer Calendar Library

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)

A comprehensive PHP library for converting between Gregorian and Khmer (Cambodian) calendar dates. This is a faithful port of the popular JavaScript [momentkh](https://github.com/ThyrithSor/momentkh) library by ThyrithSor, enhanced with modern PHP best practices and comprehensive testing.

## Features

### Core Calendar Functions
- ✅ **Gregorian to Khmer Lunar Date Conversion** - Full bidirectional date conversion
- ✅ **Buddhist Era (BE) Year Support** - Accurate BE year calculations with conversion utilities
- ✅ **Khmer New Year Calculations** - Precise calculation of ចូលឆ្នាំខ្មែរ moments using Soriyatra Lerng Sak
- ✅ **Animal Year System** - Complete 12-year animal cycle (ឆ្នាំសត្វ) support
- ✅ **Era Year System** - 10-year era cycle (ស័ក) calculations
- ✅ **Leap Year Support** - Leap month (អធិកមាស) and leap day (ចន្ទ្រាធិមាស) handling

### Formatting & Localization
- ✅ **Flexible Date Formatting** - Customizable output with Khmer formatting tokens
- ✅ **Number Conversion** - Bidirectional Arabic ↔ Khmer numeral conversion
- ✅ **Khmer Text Support** - Full Unicode Khmer character support and validation
- ✅ **Multiple Calendar Systems** - Solar and lunar month name support
- ✅ **Time Formatting** - Khmer time formatting with 12/24-hour support

### Advanced Features
- ✅ **Buddhist Holiday Calculator** - Calculate major Buddhist holidays for any year
- ✅ **Season Information** - Lunar month-based season calculations
- ✅ **Date Range Operations** - Find lunar day occurrences and date ranges
- ✅ **Era Conversion Utilities** - Convert between AD, BE, and Jolak Sakaraj (JS) eras
- ✅ **Calendar Validation** - Validate Khmer dates and check calendar constraints

### Developer Experience
- ✅ **Modern PHP (7.4+)** - Strict types, comprehensive type hints, and modern syntax
- ✅ **Comprehensive Testing** - Full PHPUnit test suite with edge case coverage
- ✅ **Static Analysis** - PHPStan level 8 compliance for maximum code quality
- ✅ **PSR-12 Standards** - Follows PHP coding standards and best practices
- ✅ **Rich Documentation** - Detailed PHPDoc annotations and usage examples

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

### KhmerDate Class

The main class for working with Khmer calendar dates.

#### Creating KhmerDate Objects

```php
// Current date and time
$now = new KhmerDate();

// From DateTime object
$dateTime = new DateTime('2024-01-15');
$khmerDate = new KhmerDate($dateTime);

// From string (various formats supported)
$khmerDate = new KhmerDate('2024-01-15');
$khmerDate = new KhmerDate('2024-01-15 14:30:00');

// From timestamp
$khmerDate = new KhmerDate(1705315800);

// Static factory methods
$khmerDate = KhmerDate::create('2024-01-15');
$khmerDate = KhmerDate::createFromDateTime($dateTime);
```

#### Core Methods

```php
$date = new KhmerDate('2024-01-15');

// Lunar date conversion
echo $date->toLunarDate();                    // Full Khmer lunar date
echo $date->toLunarDate('dN ខែm ព.ស. b');     // Custom format

// Khmer Gregorian date
echo $date->toKhmerDate();                    // Khmer numerals with Gregorian calendar

// Calendar components
echo $date->khDay();      // Lunar day (0-29): 8
echo $date->khMonth();    // Lunar month index: 2  
echo $date->khYear();     // Buddhist Era year: 2567

// Standard DateTime operations
echo $date->format('Y-m-d H:i:s');           // Standard formatting
echo $date->getTimestamp();                  // Unix timestamp
$copy = $date->copy();                       // Create copy
```

#### Date Arithmetic

```php
$date = new KhmerDate('2024-01-15');

// Add/subtract time intervals
$futureDate = $date->add('P1M');      // Add 1 month
$pastDate = $date->subtract('P7D');   // Subtract 7 days

// The original date object is modified, use copy() if needed
$newDate = $date->copy()->add('P1Y'); // Add 1 year to copy
```

### Static Methods & Utilities

#### Khmer New Year Calculation

```php
// Get exact Khmer New Year moment for any year
$newYear2024 = KhmerDate::getKhNewYearMoment(2024);
echo $newYear2024->format('Y-m-d H:i:s');  // 2024-04-14 06:46:00

// Calculate for multiple years
foreach (range(2020, 2030) as $year) {
    $newYear = KhmerDate::getKhNewYearMoment($year);
    echo "Khmer New Year $year: " . $newYear->format('Y-m-d H:i') . "\n";
}
```

#### Number Conversion

```php
// Arabic to Khmer numerals
echo KhmerDate::arabicToKhmerNumber('2024');   // ២០២៤
echo KhmerDate::arabicToKhmerNumber('15');     // ១៥

// Khmer to Arabic numerals  
echo KhmerDate::khmerToArabicNumber('២០២៤');  // 2024
echo KhmerDate::khmerToArabicNumber('១៥');     // 15

// Helper method
echo KhmerDate::getKhmerNumber(2024);          // ២០២៤
```

#### Calendar Information

```php
// Get month names
$lunarMonths = KhmerDate::getKhmerMonthNames();
// ['មិគសិរ', 'បុស្ស', 'មាឃ', 'ផល្គុន', 'ចេត្រ', 'ពិសាខ', 'ជេស្ឋ', 'អាសាឍ', 'ស្រាពណ៍', 'ភទ្របទ', 'អស្សុជ', 'កក្ដិក', 'បឋមាសាឍ', 'ទុតិយាសាឍ']

// Get animal year names (12-year cycle)
$animalYears = KhmerDate::getAnimalYearNames();  
// ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'ម្មែ', 'វក', 'រកា', 'ច', 'កុរ']

// Get era year names (10-year cycle)
$eraYears = KhmerDate::getEraYearNames();
// ['សំរឹទ្ធិស័ក', 'ឯកស័ក', 'ទោស័ក', 'ត្រីស័ក', 'ចត្វាស័ក', 'បញ្ចស័ក', 'ឆស័ក', 'សប្តស័ក', 'អដ្ឋស័ក', 'នព្វស័ក']
```

### Utility Functions (Utils Class)

```php
use PPhatDev\LunarDate\Utils;

// Buddhist holidays for a specific year
$holidays = Utils::getBuddhistHolidays(2024);
foreach ($holidays as $holiday) {
    echo "{$holiday['name']}: {$holiday['date']}\n";
    // Output: Visakha Bochea: 2024-05-22
}

// Season information based on lunar calendar
$date = new KhmerDate('2024-07-15');  
$season = Utils::getSeason($date);
echo $season['name'];        // រដូវវស្សា (Rainy Season)
echo $season['name_en'];     // Rainy Season
echo $season['description']; // Season description

// Era conversion utilities
$beYear = Utils::convertEra(2024, 'AD', 'BE');    // 2567
$adYear = Utils::convertEra(2567, 'BE', 'AD');    // 2024
$jsYear = Utils::convertEra(2024, 'AD', 'JS');    // 1385

// Find specific lunar days in a year
$fullMoons = Utils::findLunarDayOccurrences(15, 0, 2024); // All 15កើត in 2024
$newMoons = Utils::findLunarDayOccurrences(15, 1, 2024);  // All 15រោច in 2024

// Get month date ranges
$monthRange = Utils::getKhmerMonthRange(5, 2567); // ពិសាខ month in BE 2567
// Returns array of all days with lunar day information

// Date validation
$isValid = Utils::isValidKhmerDate(15, 5, 2567); // true
$isValid = Utils::isValidKhmerDate(31, 5, 2567); // false (invalid lunar day)

// Date difference in Khmer terms
$date1 = new KhmerDate('2024-01-01');
$date2 = new KhmerDate('2024-12-31');
$diff = Utils::diffInKhmer($date1, $date2);
echo "Difference: {$diff['days']} days";
```

### Formatting System

The library supports extensive formatting tokens inspired by momentkh:

### Formatting Tokens

| Token | Description | Example Output |
|-------|-------------|----------------|
| `W` | ថ្ងៃនៃសប្ដាហ៍ (Day of week full) | អង្គារ |
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

#### Advanced Formatting Examples

```php
$date = new KhmerDate('2024-01-15');

// Basic formats
echo $date->toLunarDate('W');                           // អាទិត្យ
echo $date->toLunarDate('dN');                          // ៨កើត  
echo $date->toLunarDate('dN ខែm');                      // ៨កើត ខែមិគសិរ

// Complete formats
echo $date->toLunarDate('ថ្ងៃW dN ខែm ឆ្នាំa e');          // ថ្ងៃអាទិត្យ ៨កើត ខែមិគសិរ ឆ្នាំជូត សំរឹទ្ធិស័ក
echo $date->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b');           // ៨កើត ថ្ងៃអាទិត្យ ខែមិគសិរ ព.ស. ២៥៦៧

// Mixed calendar formats
echo $date->toLunarDate('ថ្ងៃW ទី d ខែM ឆ្នាំ c');          // Mixed lunar/solar format
echo $date->toLunarDate('ព.ស. b (គ.ស. c)');              // ព.ស. ២៥៦៧ (គ.ស. ២០២៤)
```

### KhmerFormatter Class

For advanced formatting needs, use the `KhmerFormatter` class directly:

```php
use PPhatDev\LunarDate\KhmerFormatter;

$formatter = new KhmerFormatter();

// Number formatting
echo $formatter->toKhmerNumber('2024');           // ២០២៤
echo $formatter->fromKhmerNumber('២០២៤');        // 2024
echo $formatter->formatNumber(1234.56, 2);       // ១,២៣៤.៥៦

// Date formatting
$date = new DateTime('2024-01-15');
echo $formatter->formatDate($date, 'full');      // Full Khmer Gregorian date
echo $formatter->getDayName($date);              // អាទិត្យ
echo $formatter->getMonthName($date);            // មករា

// Currency formatting
echo $formatter->formatCurrency(1500.50);       // ១,៥០០.៥០ រៀល

// Time formatting
echo $formatter->formatTime($date, true);       // 24-hour format
echo $formatter->formatTime($date, false);      // 12-hour format

// Text validation
$isKhmer = $formatter->isKhmerText('ភាសាខ្មែរ');  // true
$isKhmer = $formatter->isKhmerText('English');   // false
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

## Understanding the Khmer Calendar System

The Khmer calendar is a sophisticated lunisolar system that combines lunar phases with solar year calculations:

### Calendar Structure

#### Months (ខែ)
The Khmer calendar has **14 possible months** per year:

**Regular Months** (12 months):
- មិគសិរ, បុស្ស, មាឃ, ផល្គុន, ចេត្រ, ពិសាខ, ជេស្ឋ, អាសាឍ, ស្រាពណ៍, ភទ្របទ, អស្សុជ, កក្ដិក

**Leap Months** (occur in leap years):
- បឋមាសាឍ (first អាសាឍ)
- ទុតិយាសាឍ (second អាសាឍ)

#### Days (ថ្ងៃ)
Each lunar month follows the moon phases:
- **Waxing Moon** (កើត): ១កើត, ២កើត, ... ១៥កើត (15 days)
- **Waning Moon** (រោច): ១រោច, ២រោច, ... ១៤រោច or ១៥រោច (14-15 days)

#### Year Systems (ឆ្នាំ)
**Animal Years** (12-year cycle):
ជូត, ឆ្លូវ, ខាល, ថោះ, រោង, ម្សាញ់, មមី, ម្មែ, វក, រកា, ច, កុរ

**Era Years** (10-year cycle):
សំរឹទ្ធិស័ក, ឯកស័ក, ទោស័ក, ត្រីស័ក, ចត្វាស័ក, បញ្ចស័ក, ឆស័ក, សប្តស័ក, អដ្ឋស័ក, នព្វស័ក

**Buddhist Era (BE)**: Gregorian year + 543/544 (depending on the time of year)

### Leap Year Types

The Khmer calendar has three types of years:

1. **Regular Year** (354 days): 12 months, normal lunar cycle
2. **Leap Month Year** (អធិកមាស): 13 months (384 days) - adds បឋមាសាឍ/ទុតិយាសាឍ
3. **Leap Day Year** (ចន្ទ្រាធិមាស): Extra day added to ជេស្ឋ month (355 days)

### Key Calculations

The library implements complex calculations including:

- **Bodithey (បូតិថី)**: Lunar calendar adjustments
- **Avoman (អវមាន)**: Leap month calculations  
- **Ahargun (អហរគុណ)**: Leap day determinations
- **Soriyatra Lerng Sak**: New Year timing calculations

## Advanced Usage Examples

### Working with Lunar Days

```php
// Find all full moon days (15កើត) in 2024
$fullMoons = Utils::findLunarDayOccurrences(15, 0, 2024);
foreach ($fullMoons as $fullMoon) {
    echo "Full Moon: {$fullMoon['gregorian']} - {$fullMoon['khmer']}\n";
}

// Find all new moon days (15រោច) in 2024  
$newMoons = Utils::findLunarDayOccurrences(15, 1, 2024);
```

### Buddhist Holiday Calculations

```php
// Get all Buddhist holidays for 2024
$holidays = Utils::getBuddhistHolidays(2024);
foreach ($holidays as $holiday) {
    echo "{$holiday['name']}: {$holiday['date']} ({$holiday['type']})\n";
}

// Example output:
// Visakha Bochea: 2024-05-22 (major)
// Royal Ploughing Ceremony: 2024-05-09 (cultural)
// Pchum Ben: 2024-10-02 (ancestor)
```

### Season Information

```php
$date = new KhmerDate('2024-07-15');
$season = Utils::getSeason($date);

echo "Season: {$season['name']} ({$season['name_en']})\n";
echo "Description: {$season['description']}\n";
echo "Months: " . implode(', ', $season['months']) . "\n";

// Output:
// Season: រដូវវស្សា (Rainy Season)
// Description: The monsoon season with heavy rainfall
// Months: ស្រាពណ៍, ភទ្របទ, អស្សុជ
```

### Era Conversion Examples

```php
// Convert between different calendar systems
$currentYear = 2024;

$beYear = Utils::convertEra($currentYear, 'AD', 'BE');    // 2567
$jsYear = Utils::convertEra($currentYear, 'AD', 'JS');    // 1385

// Convert back
$adFromBE = Utils::convertEra($beYear, 'BE', 'AD');       // 2024
$adFromJS = Utils::convertEra($jsYear, 'JS', 'AD');       // 2024

echo "Gregorian: $currentYear\n";
echo "Buddhist Era: $beYear\n"; 
echo "Jolak Sakaraj: $jsYear\n";
```

### Date Range Operations

```php
// Get all days in a specific Khmer month
$monthData = Utils::getKhmerMonthRange(5, 2567); // ពិសាខ month, BE 2567

foreach ($monthData as $day) {
    echo "Day {$day['day']}: {$day['formatted']} ({$day['moonStatus']})\n";
}

// Validate Khmer dates
$isValid = Utils::isValidKhmerDate(15, 5, 2567);  // true - valid full moon
$isValid = Utils::isValidKhmerDate(31, 5, 2567);  // false - invalid lunar day
$isValid = Utils::isValidKhmerDate(15, 15, 2567); // false - invalid month
```

## Development & Testing

### Development Setup

```bash
# Clone the repository
git clone https://github.com/pphatdev/lunar-date.git
cd lunar-date

# Install dependencies
composer install

# Run examples to test functionality
php examples/demo.php
php examples/momentkh_examples.php
php examples/new_year.php
```

### Quality Assurance

This library maintains high code quality standards:

```bash
# Run complete test suite
composer test

# Generate code coverage report
composer test:coverage

# Run all quality checks (tests, coding standards, static analysis)
composer quality

# Individual quality tools
composer cs-check        # Check PSR-12 coding standards
composer cs-fix          # Fix coding standard violations
composer stan            # Run PHPStan static analysis (level 8)
```

### Testing Coverage

The library includes comprehensive test coverage:

- **Unit Tests**: All classes and methods tested with PHPUnit
- **Integration Tests**: End-to-end calendar conversion testing
- **Edge Case Testing**: Boundary conditions and error scenarios
- **Regression Testing**: Known calculation values verified
- **Performance Tests**: Efficiency of core algorithms

### Code Quality Metrics

- ✅ **PHPStan Level 8**: Strictest static analysis
- ✅ **PSR-12 Compliance**: Modern PHP coding standards
- ✅ **Type Safety**: Comprehensive type hints and strict types
- ✅ **Error Handling**: Proper exception handling and validation
- ✅ **Documentation**: Complete PHPDoc annotations

## Project Structure

```
src/
├── KhmerDate.php           # Main date class
├── KhmerFormatter.php      # Formatting and localization
├── KhmerCalculator.php     # Core calendar calculations
├── SoriyatraLerngSak.php   # New Year calculations
├── Utils.php               # Utility functions
└── Constants.php           # Calendar constants

examples/
├── demo.php               # Basic usage examples
├── momentkh_examples.php  # MomentKH compatibility demos
└── new_year.php          # New Year calculation examples

tests/
├── KhmerDateTest.php      # Main class tests
├── KhmerCalculatorTest.php # Calculation tests  
├── ConstantsTest.php      # Constants validation
└── simple_test.php        # Basic functionality test
```

## Contributing

We welcome contributions! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

### Contribution Guidelines

1. **Fork the repository** and create a feature branch
2. **Follow PSR-12** coding standards
3. **Add tests** for new functionality
4. **Update documentation** as needed
5. **Run quality checks** before submitting

```bash
# Before submitting PR
composer quality    # Run all quality checks
composer test       # Ensure all tests pass
composer cs-fix     # Fix any style issues
```

### Development Priorities

- **Performance Optimization**: Improve calculation efficiency
- **Extended Formatting**: More formatting options and tokens
- **Historical Accuracy**: Enhance historical date accuracy
- **Documentation**: Expand examples and use cases
- **Internationalization**: Support for additional languages

## Requirements

- **PHP 7.4+**: Modern PHP with strict types support
- **No External Dependencies**: Uses only PHP standard library
- **Composer**: For autoloading and dependency management

## License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## Credits & Acknowledgments

### Original Work
- **[ThyrithSor](https://github.com/ThyrithSor)** - Creator of the original [momentkh](https://github.com/ThyrithSor/momentkh) JavaScript library
- **Moment.js Team** - Inspiration for the API design philosophy

### Academic References
- **"Pratitin Soryakkatik-Chankatik 1900-1999"** by Mr. Roath Kim Soeun
- **[cam-cc.org](http://www.cam-cc.org)** - Cambodian calendar calculations
- **[dahlina.com](http://www.dahlina.com)** - Historical calendar references

### Special Thanks
Special appreciation to ThyrithSor for creating the foundational momentkh library and making Khmer calendar calculations accessible to developers worldwide. This PHP port aims to bring the same functionality to the PHP ecosystem while maintaining compatibility with the original JavaScript API.

## Related Projects

- **[momentkh](https://github.com/ThyrithSor/momentkh)** - Original JavaScript library
- **[moment.js](https://momentjs.com/)** - Inspiration for the API design

## Support

- **Documentation**: This README and PHPDoc comments
- **Examples**: Check the `examples/` directory for usage patterns
- **Issues**: Report bugs and request features via GitHub Issues
- **Discussions**: Use GitHub Discussions for questions and community support

---

**Note**: This library focuses on historical accuracy and cultural authenticity for Khmer calendar calculations. While extensively tested, please verify critical date calculations for important applications.
