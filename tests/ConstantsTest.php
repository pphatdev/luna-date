<?php

// declare(strict_types=1);

namespace PPhatDev\LunarDate\Tests;

use PHPUnit\Framework\TestCase;
use PPhatDev\LunarDate\Constants;

/**
 * Test suite for Constants class
 *
 * Validates all constant definitions and their values
 */
class ConstantsTest extends TestCase
{
    public function testLunarMonthsConstant(): void
    {
        $lunarMonths = Constants::LUNAR_MONTHS;

        // Test that all expected months are present
        $expectedMonths = [
            'មិគសិរ',
            'បុស្ស',
            'មាឃ',
            'ផល្គុន',
            'ចេត្រ',
            'ពិសាខ',
            'ជេស្ឋ',
            'អាសាឍ',
            'ស្រាពណ៍',
            'ភទ្របទ',
            'អស្សុជ',
            'កត្ដិក',
            'បឋមាសាឍ',
            'ទុតិយាសាឍ'
        ];

        foreach ($expectedMonths as $month) {
            $this->assertArrayHasKey($month, $lunarMonths, "Missing lunar month: $month");
        }

        // Test correct number of months
        $this->assertCount(14, $lunarMonths, 'Should have 14 lunar months including leap months');

        // Test sequential numbering for regular months (0-11)
        $this->assertEquals(0, $lunarMonths['មិគសិរ']);
        $this->assertEquals(1, $lunarMonths['បុស្ស']);
        $this->assertEquals(11, $lunarMonths['កត្ដិក']);

        // Test leap months have correct indices
        $this->assertEquals(12, $lunarMonths['បឋមាសាឍ']);
        $this->assertEquals(13, $lunarMonths['ទុតិយាសាឍ']);
    }

    public function testSolarMonthsConstant(): void
    {
        $solarMonths = Constants::SOLAR_MONTHS;

        // Test that all expected months are present
        $expectedMonths = [
            'មករា',
            'កុម្ភៈ',
            'មីនា',
            'មេសា',
            'ឧសភា',
            'មិថុនា',
            'កក្កដា',
            'សីហា',
            'កញ្ញា',
            'តុលា',
            'វិច្ឆិកា',
            'ធ្នូ'
        ];

        foreach ($expectedMonths as $month) {
            $this->assertArrayHasKey($month, $solarMonths, "Missing solar month: $month");
        }

        // Test correct number of months
        $this->assertCount(12, $solarMonths, 'Should have 12 solar months');

        // Test sequential numbering (0-11)
        $this->assertEquals(0, $solarMonths['មករា']);
        $this->assertEquals(11, $solarMonths['ធ្នូ']);
    }

    public function testAnimalYearsConstant(): void
    {
        $animalYears = Constants::ANIMAL_YEARS;

        // Test correct number of animals
        $this->assertCount(12, $animalYears, 'Should have 12 animal years');

        // Test some known animals
        $expectedAnimals = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់'];
        foreach ($expectedAnimals as $animal) {
            $this->assertContains($animal, $animalYears, "Missing animal: $animal");
        }
    }

    public function testEraYearsConstant(): void
    {
        $eraYears = Constants::ERA_YEARS;

        // Test correct number of eras
        $this->assertCount(10, $eraYears, 'Should have 10 era years');

        // Test some known eras
        $this->assertEquals('សំរឹទ្ធិស័ក', $eraYears[0]);
        $this->assertEquals('នព្វស័ក', $eraYears[9]);
    }

    public function testMoonStatusConstant(): void
    {
        $moonStatus = Constants::MOON_STATUS;

        // Test correct values
        $this->assertEquals(0, $moonStatus['កើត'], 'Waxing moon should be 0');
        $this->assertEquals(1, $moonStatus['រោច'], 'Waning moon should be 1');

        // Test count
        $this->assertCount(2, $moonStatus, 'Should have 2 moon statuses');
    }

    public function testMoonStatusShortConstant(): void
    {
        $moonStatusShort = Constants::MOON_STATUS_SHORT;

        $this->assertCount(2, $moonStatusShort, 'Should have 2 short moon statuses');
        $this->assertEquals('ក', $moonStatusShort[0]);
        $this->assertEquals('រ', $moonStatusShort[1]);
    }

    public function testWeekdaysConstant(): void
    {
        $weekdays = Constants::WEEKDAYS;

        // Test correct number of weekdays
        $this->assertCount(7, $weekdays, 'Should have 7 weekdays');

        // Test some known weekdays
        $this->assertEquals('អាទិត្យ', $weekdays[0]);
        $this->assertEquals('ចន្ទ', $weekdays[1]);
        $this->assertEquals('សៅរ៍', $weekdays[6]);
    }

    public function testWeekdaysShortConstant(): void
    {
        $weekdaysShort = Constants::WEEKDAYS_SHORT;

        $this->assertCount(7, $weekdaysShort, 'Should have 7 short weekdays');
        $this->assertEquals('អា', $weekdaysShort[0]);
        $this->assertEquals('ស', $weekdaysShort[6]);
    }

    public function testMonthsConstant(): void
    {
        $months = Constants::MONTHS;

        // Should be same as solar months but as array
        $this->assertCount(12, $months, 'Should have 12 months');
        $this->assertEquals('មករា', $months[0]);
        $this->assertEquals('ធ្នូ', $months[11]);
    }

    public function testKhNewYearMomentsConstant(): void
    {
        $newYearMoments = Constants::KH_NEW_YEAR_MOMENTS;

        // Test that it's an array
        $this->assertIsArray($newYearMoments);

        // Test some known years
        if (isset($newYearMoments['2014'])) {
            $this->assertIsString($newYearMoments['2014']);
            $this->assertStringContainsString('2014', $newYearMoments['2014']);
        }
    }

    public function testKhmerNumbersConstant(): void
    {
        $khmerNumbers = Constants::KHMER_NUMBERS;

        // Test correct number of digits
        $this->assertCount(10, $khmerNumbers, 'Should have 10 Khmer number symbols');

        // Test some known mappings
        $this->assertEquals('០', $khmerNumbers['0']);
        $this->assertEquals('១', $khmerNumbers['1']);
        $this->assertEquals('៩', $khmerNumbers['9']);

        // Test all digits are present
        for ($i = 0; $i <= 9; $i++) {
            $this->assertArrayHasKey((string)$i, $khmerNumbers, "Missing Khmer number for: $i");
        }
    }

    public function testArabicNumbersConstant(): void
    {
        $arabicNumbers = Constants::ARABIC_NUMBERS;

        // Test correct number of digits
        $this->assertCount(10, $arabicNumbers, 'Should have 10 Arabic number mappings');

        // Test some known mappings (reverse of Khmer numbers)
        $this->assertEquals('0', $arabicNumbers['០']);
        $this->assertEquals('1', $arabicNumbers['១']);
        $this->assertEquals('9', $arabicNumbers['៩']);

        // Test bidirectional consistency
        foreach (Constants::KHMER_NUMBERS as $arabic => $khmer) {
            $this->assertEquals($arabic, $arabicNumbers[$khmer], "Inconsistent mapping for $arabic <-> $khmer");
        }
    }

    /**
     * Test that constants are immutable (cannot be modified)
     */
    public function testConstantsAreReadOnly(): void
    {
        // These should be constants and not modifiable
        // This test ensures the constants are properly defined
        $reflection = new \ReflectionClass(Constants::class);
        $constants = $reflection->getConstants();

        $expectedConstants = [
            'LUNAR_MONTHS',
            'SOLAR_MONTHS',
            'ANIMAL_YEARS',
            'ERA_YEARS',
            'MOON_STATUS',
            'MOON_STATUS_SHORT',
            'WEEKDAYS',
            'WEEKDAYS_SHORT',
            'MONTHS',
            'KH_NEW_YEAR_MOMENTS',
            'KHMER_NUMBERS',
            'ARABIC_NUMBERS'
        ];

        foreach ($expectedConstants as $constant) {
            $this->assertArrayHasKey($constant, $constants, "Missing constant: $constant");
        }
    }

    /**
     * Test data integrity between related constants
     */
    public function testDataIntegrity(): void
    {
        // Solar months count should match MONTHS array count
        $this->assertCount(
            count(Constants::SOLAR_MONTHS),
            Constants::MONTHS,
            'SOLAR_MONTHS and MONTHS should have same count'
        );

        // Moon status and short status should have same count
        $this->assertCount(
            count(Constants::MOON_STATUS),
            Constants::MOON_STATUS_SHORT,
            'MOON_STATUS and MOON_STATUS_SHORT should have same count'
        );

        // Weekdays and short weekdays should have same count
        $this->assertCount(
            count(Constants::WEEKDAYS),
            Constants::WEEKDAYS_SHORT,
            'WEEKDAYS and WEEKDAYS_SHORT should have same count'
        );

        // Khmer and Arabic numbers should have same count
        $this->assertCount(
            count(Constants::KHMER_NUMBERS),
            Constants::ARABIC_NUMBERS,
            'KHMER_NUMBERS and ARABIC_NUMBERS should have same count'
        );
    }
}
