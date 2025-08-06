<?php

declare(strict_types=1);

namespace PPhatDev\LunarDate\Tests;

use PHPUnit\Framework\TestCase;
use PPhatDev\LunarDate\KhmerCalculator;
use PPhatDev\LunarDate\Constants;
use DateTime;
use InvalidArgumentException;
use RuntimeException;

/**
 * Comprehensive test suite for KhmerCalculator
 *
 * Tests all calculation methods with various edge cases and validation scenarios
 */
class KhmerCalculatorTest extends TestCase
{
    /**
     * Test data for known calculations
     */
    private const TEST_YEARS = [
        2567 => [
            'bodithey' => 26,
            'avoman' => 310,
            'aharkun' => 937623,
            'isLeapMonth' => true,
            'isLeapDay' => false,
            'daysInYear' => 384
        ],
        2568 => [
            'bodithey' => 8,
            'avoman' => 184,
            'aharkun' => 937989,
            'isLeapMonth' => false,
            'isLeapDay' => false,
            'daysInYear' => 354
        ]
    ];

    public function testGetBodithey(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getBodithey($year);
            $this->assertEquals(
                $expected['bodithey'],
                $result,
                "Bodithey calculation failed for year $year"
            );

            // Ensure result is within valid range
            $this->assertGreaterThanOrEqual(0, $result);
            $this->assertLessThan(30, $result);
        }
    }

    public function testGetBoditheyWithNegativeYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buddhist Era year must be positive');
        KhmerCalculator::getBodithey(-1);
    }

    public function testGetAvoman(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getAvoman($year);
            $this->assertEquals(
                $expected['avoman'],
                $result,
                "Avoman calculation failed for year $year"
            );

            // Ensure result is within valid range
            $this->assertGreaterThanOrEqual(0, $result);
            $this->assertLessThan(692, $result);
        }
    }

    public function testGetAvomanWithNegativeYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buddhist Era year must be positive');
        KhmerCalculator::getAvoman(-1);
    }

    public function testGetAharkun(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getAharkun($year);
            $this->assertEquals(
                $expected['aharkun'],
                $result,
                "Aharkun calculation failed for year $year"
            );

            // Ensure result is positive
            $this->assertGreaterThan(0, $result);
        }
    }

    public function testGetAharkunWithNegativeYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buddhist Era year must be positive');
        KhmerCalculator::getAharkun(-1);
    }

    public function testKromthupul(): void
    {
        $result = KhmerCalculator::kromthupul(2567);
        $this->assertGreaterThan(0, $result);
        $this->assertLessThanOrEqual(800, $result);
    }

    public function testIsKhmerSolarLeap(): void
    {
        // Test known solar leap years
        $solarLeapYear = 2567; // This should be calculated based on Khmer solar calendar
        $result = KhmerCalculator::isKhmerSolarLeap($solarLeapYear);
        $this->assertIsInt($result);
        $this->assertContains($result, [0, 1]);
    }

    public function testGetAharkunMod(): void
    {
        $result = KhmerCalculator::getAharkunMod(2567);
        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertLessThan(800, $result);
    }

    public function testGetBoditheyLeap(): void
    {
        // Test all possible leap types
        $validLeapTypes = [0, 1, 2, 3];

        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getBoditheyLeap($year);
            $this->assertContains(
                $result,
                $validLeapTypes,
                "Invalid bodithey leap type for year $year"
            );
        }
    }

    public function testGetProtetinLeap(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getProtetinLeap($year);
            $this->assertContains($result, [0, 1, 2], "Invalid protetin leap type for year $year");
        }
    }

    public function testIsKhmerLeapMonth(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::isKhmerLeapMonth($year);
            $this->assertEquals(
                $expected['isLeapMonth'],
                $result,
                "Leap month check failed for year $year"
            );
        }
    }

    public function testIsKhmerLeapDay(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::isKhmerLeapDay($year);
            $this->assertEquals(
                $expected['isLeapDay'],
                $result,
                "Leap day check failed for year $year"
            );
        }
    }

    public function testIsGregorianLeap(): void
    {
        // Test known Gregorian leap years
        $this->assertTrue(KhmerCalculator::isGregorianLeap(2024)); // Divisible by 4
        $this->assertTrue(KhmerCalculator::isGregorianLeap(2000)); // Divisible by 400
        $this->assertFalse(KhmerCalculator::isGregorianLeap(1900)); // Divisible by 100 but not 400
        $this->assertFalse(KhmerCalculator::isGregorianLeap(2023)); // Not divisible by 4
    }

    public function testGetNumberOfDayInKhmerMonth(): void
    {
        $regularYear = 2567; // Non-leap year
        $leapDayYear = 2568;  // Leap day year (if applicable)

        // Test regular months
        $mikasir = Constants::LUNAR_MONTHS['មិគសិរ']; // Month 0
        $this->assertEquals(29, KhmerCalculator::getNumberOfDayInKhmerMonth($mikasir, $regularYear));

        $poss = Constants::LUNAR_MONTHS['បុស្ស']; // Month 1
        $this->assertEquals(30, KhmerCalculator::getNumberOfDayInKhmerMonth($poss, $regularYear));

        // Test Adhikameas months (always 30 days)
        $porthomeas = Constants::LUNAR_MONTHS['បឋមាសាឍ'];
        $this->assertEquals(30, KhmerCalculator::getNumberOfDayInKhmerMonth($porthomeas, $regularYear));

        $dutiyeas = Constants::LUNAR_MONTHS['ទុតិយាសាឍ'];
        $this->assertEquals(30, KhmerCalculator::getNumberOfDayInKhmerMonth($dutiyeas, $regularYear));
    }

    public function testGetNumberOfDayInKhmerMonthWithInvalidMonth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Khmer month index: 99');
        KhmerCalculator::getNumberOfDayInKhmerMonth(99, 2567);
    }

    public function testGetNumberOfDayInKhmerMonthWithNegativeYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buddhist Era year must be positive');
        KhmerCalculator::getNumberOfDayInKhmerMonth(0, -1);
    }

    public function testGetNumberOfDayInKhmerYear(): void
    {
        foreach (self::TEST_YEARS as $year => $expected) {
            $result = KhmerCalculator::getNumberOfDayInKhmerYear($year);
            $this->assertEquals(
                $expected['daysInYear'],
                $result,
                "Days in year calculation failed for year $year"
            );
        }
    }

    public function testGetNumberOfDayInGregorianYear(): void
    {
        $this->assertEquals(366, KhmerCalculator::getNumberOfDayInGregorianYear(2024)); // Leap year
        $this->assertEquals(365, KhmerCalculator::getNumberOfDayInGregorianYear(2023)); // Regular year
    }

    public function testGetBEYear(): void
    {
        // Test before Visakha Bochea
        $dateBeforeVB = new DateTime('2024-03-15');
        $beYear = KhmerCalculator::getBEYear($dateBeforeVB);
        $this->assertEquals(2567, $beYear); // 2024 + 543

        // Test after Visakha Bochea
        $dateAfterVB = new DateTime('2024-06-15');
        $beYear = KhmerCalculator::getBEYear($dateAfterVB);
        $this->assertEquals(2568, $beYear); // 2024 + 544
    }

    public function testGetMaybeBEYear(): void
    {
        // Test early months (before May)
        $earlyDate = new DateTime('2024-03-15');
        $beYear = KhmerCalculator::getMaybeBEYear($earlyDate);
        $this->assertEquals(2567, $beYear); // 2024 + 543

        // Test later months (after April)
        $lateDate = new DateTime('2024-06-15');
        $beYear = KhmerCalculator::getMaybeBEYear($lateDate);
        $this->assertEquals(2568, $beYear); // 2024 + 544
    }

    public function testGetVisakhaBochea(): void
    {
        $visakhaBochea = KhmerCalculator::getVisakhaBochea(2024);
        $this->assertInstanceOf(DateTime::class, $visakhaBochea);
        $this->assertEquals('2024', $visakhaBochea->format('Y'));

        // Should be in April-June range typically
        $month = (int)$visakhaBochea->format('n');
        $this->assertGreaterThanOrEqual(4, $month);
        $this->assertLessThanOrEqual(6, $month);
    }

    public function testGetVisakhaBocheaWithInvalidYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Gregorian year must be positive');
        KhmerCalculator::getVisakhaBochea(0);
    }

    public function testGetJolakSakarajYear(): void
    {
        $date = new DateTime('2024-06-15');
        $jolakYear = KhmerCalculator::getJolakSakarajYear($date);
        $this->assertIsInt($jolakYear);
        $this->assertGreaterThan(1200, $jolakYear); // Should be reasonable range
    }

    public function testGetAnimalYear(): void
    {
        $date = new DateTime('2024-06-15');
        $animalYear = KhmerCalculator::getAnimalYear($date);
        $this->assertIsInt($animalYear);
        $this->assertGreaterThanOrEqual(0, $animalYear);
        $this->assertLessThan(12, $animalYear);
    }

    public function testGetKhmerLunarDay(): void
    {
        // Test waxing moon (first half of month)
        $waxingDay = KhmerCalculator::getKhmerLunarDay(5);
        $this->assertEquals(6, $waxingDay['count']);
        $this->assertEquals(Constants::MOON_STATUS['កើត'], $waxingDay['moonStatus']);

        // Test waning moon (second half of month)
        $waningDay = KhmerCalculator::getKhmerLunarDay(20);
        $this->assertEquals(6, $waningDay['count']); // 20 % 15 + 1 = 6
        $this->assertEquals(Constants::MOON_STATUS['រោច'], $waningDay['moonStatus']);

        // Test edge cases
        $firstDay = KhmerCalculator::getKhmerLunarDay(0);
        $this->assertEquals(1, $firstDay['count']);
        $this->assertEquals(Constants::MOON_STATUS['កើត'], $firstDay['moonStatus']);

        $lastDay = KhmerCalculator::getKhmerLunarDay(29);
        $this->assertEquals(15, $lastDay['count']);
        $this->assertEquals(Constants::MOON_STATUS['រោច'], $lastDay['moonStatus']);
    }

    public function testNextMonthOf(): void
    {
        // Test regular month progression
        $mikasir = Constants::LUNAR_MONTHS['មិគសិរ'];
        $nextMonth = KhmerCalculator::nextMonthOf($mikasir, 2567);
        $this->assertEquals(Constants::LUNAR_MONTHS['បុស្ស'], $nextMonth);

        // Test leap month scenario
        $jetha = Constants::LUNAR_MONTHS['ជេស្ឋ'];
        $nextMonthLeap = KhmerCalculator::nextMonthOf($jetha, 2567); // Leap month year
        $this->assertEquals(Constants::LUNAR_MONTHS['បឋមាសាឍ'], $nextMonthLeap);

        $nextMonthNormal = KhmerCalculator::nextMonthOf($jetha, 2568); // Normal year
        $this->assertEquals(Constants::LUNAR_MONTHS['អាសាឍ'], $nextMonthNormal);

        // Test Adhikameas progression
        $porthomeas = Constants::LUNAR_MONTHS['បឋមាសាឍ'];
        $nextFromPorthomeas = KhmerCalculator::nextMonthOf($porthomeas, 2567);
        $this->assertEquals(Constants::LUNAR_MONTHS['ទុតិយាសាឍ'], $nextFromPorthomeas);

        $dutiyeas = Constants::LUNAR_MONTHS['ទុតិយាសាឍ'];
        $nextFromDutiyeas = KhmerCalculator::nextMonthOf($dutiyeas, 2567);
        $this->assertEquals(Constants::LUNAR_MONTHS['ស្រាពណ៍'], $nextFromDutiyeas);
    }

    public function testNextMonthOfWithInvalidMonth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Khmer month: 99');
        KhmerCalculator::nextMonthOf(99, 2567);
    }

    public function testNextMonthOfWithNegativeYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buddhist Era year must be positive');
        KhmerCalculator::nextMonthOf(0, -1);
    }

    /**
     * Test edge cases and boundary conditions
     */
    public function testEdgeCases(): void
    {
        // Test very early Buddhist year
        $earlyYear = 1;
        $this->assertIsInt(KhmerCalculator::getBodithey($earlyYear));
        $this->assertIsInt(KhmerCalculator::getAvoman($earlyYear));
        $this->assertIsInt(KhmerCalculator::getAharkun($earlyYear));

        // Test large Buddhist year
        $largeYear = 9999;
        $this->assertIsInt(KhmerCalculator::getBodithey($largeYear));
        $this->assertIsInt(KhmerCalculator::getAvoman($largeYear));
        $this->assertIsInt(KhmerCalculator::getAharkun($largeYear));
    }

    /**
     * Test consistency between related calculations
     */
    public function testCalculationConsistency(): void
    {
        $testYears = [2567, 2568, 2569, 2570];

        foreach ($testYears as $year) {
            // Test that leap month and leap day calculations are consistent with protetin leap
            $isLeapMonth = KhmerCalculator::isKhmerLeapMonth($year);
            $isLeapDay = KhmerCalculator::isKhmerLeapDay($year);
            $protetinLeap = KhmerCalculator::getProtetinLeap($year);

            if ($protetinLeap === 1) {
                $this->assertTrue($isLeapMonth, "Year $year should be leap month year");
                $this->assertFalse($isLeapDay, "Year $year should not be leap day year");
            } elseif ($protetinLeap === 2) {
                $this->assertFalse($isLeapMonth, "Year $year should not be leap month year");
                $this->assertTrue($isLeapDay, "Year $year should be leap day year");
            } else {
                $this->assertFalse($isLeapMonth, "Year $year should not be leap month year");
                $this->assertFalse($isLeapDay, "Year $year should not be leap day year");
            }

            // Test that days in year calculation is consistent with leap status
            $daysInYear = KhmerCalculator::getNumberOfDayInKhmerYear($year);
            if ($isLeapMonth) {
                $this->assertEquals(384, $daysInYear, "Leap month year $year should have 384 days");
            } elseif ($isLeapDay) {
                $this->assertEquals(355, $daysInYear, "Leap day year $year should have 355 days");
            } else {
                $this->assertEquals(354, $daysInYear, "Regular year $year should have 354 days");
            }
        }
    }
}
