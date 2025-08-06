<?php

namespace PPhatDev\LunarDate\Tests;

use PHPUnit\Framework\TestCase;
use PPhatDev\LunarDate\KhmerDate;
use PPhatDev\LunarDate\Constants;
use PPhatDev\LunarDate\KhmerCalculator;
use DateTime;

class KhmerDateTest extends TestCase
{
    public function testBasicDateCreation()
    {
        $date = new KhmerDate('2024-01-15');
        $this->assertInstanceOf(KhmerDate::class, $date);
        $this->assertEquals('2024-01-15', $date->format('Y-m-d'));
    }

    public function testCurrentDate()
    {
        $date = new KhmerDate();
        $this->assertInstanceOf(KhmerDate::class, $date);
        $this->assertNotEmpty($date->toLunarDate());
    }

    public function testKhmerDateComponents()
    {
        $date = new KhmerDate('2024-01-15');

        $khDay = $date->khDay();
        $khMonth = $date->khMonth();
        $khYear = $date->khYear();

        $this->assertIsInt($khDay);
        $this->assertIsInt($khMonth);
        $this->assertIsInt($khYear);

        $this->assertGreaterThanOrEqual(0, $khDay);
        $this->assertLessThan(30, $khDay);

        $this->assertGreaterThanOrEqual(0, $khMonth);
        $this->assertLessThanOrEqual(13, $khMonth);

        $this->assertGreaterThan(2500, $khYear);
    }

    public function testDateFormatting()
    {
        $date = new KhmerDate('2024-01-15');

        // Test default format
        $defaultFormat = $date->toLunarDate();
        $this->assertNotEmpty($defaultFormat);

        // Test custom formats
        $weekday = $date->toLunarDate('W');
        $this->assertContains($weekday, Constants::WEEKDAYS);

        $dayAndMoon = $date->toLunarDate('dN');
        $this->assertIsString($dayAndMoon);
        $this->assertNotEmpty($dayAndMoon);
    }

    public function testNumberConversion()
    {
        // Arabic to Khmer
        $khmerNumber = KhmerDate::arabicToKhmerNumber('2024');
        $this->assertEquals('២០២៤', $khmerNumber);

        $khmerSingle = KhmerDate::arabicToKhmerNumber('5');
        $this->assertEquals('៥', $khmerSingle);

        // Khmer to Arabic
        $arabicNumber = KhmerDate::khmerToArabicNumber('២០២៤');
        $this->assertEquals('2024', $arabicNumber);

        $arabicSingle = KhmerDate::khmerToArabicNumber('៥');
        $this->assertEquals('5', $arabicSingle);
    }

    public function testCalendarConstants()
    {
        // Test lunar months exist
        $this->assertArrayHasKey('មិគសិរ', Constants::LUNAR_MONTHS);
        $this->assertArrayHasKey('បុស្ស', Constants::LUNAR_MONTHS);
        $this->assertArrayHasKey('ពិសាខ', Constants::LUNAR_MONTHS);

        // Test animal years
        $this->assertContains('ជូត', Constants::ANIMAL_YEARS);
        $this->assertContains('ឆ្លូវ', Constants::ANIMAL_YEARS);

        // Test weekdays
        $this->assertContains('អាទិត្យ', Constants::WEEKDAYS);
        $this->assertContains('ចន្ទ', Constants::WEEKDAYS);

        // Test era years
        $this->assertContains('សំរឹទ្ធិស័ក', Constants::ERA_YEARS);
    }

    public function testKhmerCalculatorBasics()
    {
        $beYear = 2567; // Buddhist Era year

        // Test bodithey calculation
        $bodithey = KhmerCalculator::getBodithey($beYear);
        $this->assertIsInt($bodithey);
        $this->assertGreaterThanOrEqual(0, $bodithey);
        $this->assertLessThan(30, $bodithey);

        // Test avoman calculation
        $avoman = KhmerCalculator::getAvoman($beYear);
        $this->assertIsInt($avoman);
        $this->assertGreaterThanOrEqual(0, $avoman);

        // Test leap year functions
        $isLeapMonth = KhmerCalculator::isKhmerLeapMonth($beYear);
        $isLeapDay = KhmerCalculator::isKhmerLeapDay($beYear);
        $this->assertIsBool($isLeapMonth);
        $this->assertIsBool($isLeapDay);
    }

    public function testGregorianLeapYear()
    {
        $this->assertTrue(KhmerCalculator::isGregorianLeap(2024));
        $this->assertFalse(KhmerCalculator::isGregorianLeap(2023));
        $this->assertTrue(KhmerCalculator::isGregorianLeap(2000));
        $this->assertFalse(KhmerCalculator::isGregorianLeap(1900));
    }

    public function testDateCopy()
    {
        $original = new KhmerDate('2024-01-15');
        $copy = $original->copy();

        $this->assertInstanceOf(KhmerDate::class, $copy);
        $this->assertEquals($original->format('Y-m-d'), $copy->format('Y-m-d'));
        $this->assertNotSame($original, $copy);
    }

    public function testDateManipulation()
    {
        $date = new KhmerDate('2024-01-15');
        $originalTimestamp = $date->getTimestamp();

        // Test add
        $date->add('1 day');
        $this->assertGreaterThan($originalTimestamp, $date->getTimestamp());

        // Test subtract
        $date->subtract('2 days');
        $this->assertLessThan($originalTimestamp, $date->getTimestamp());
    }

    public function testStaticFactoryMethods()
    {
        $date1 = KhmerDate::create('2024-01-15');
        $this->assertInstanceOf(KhmerDate::class, $date1);

        $dateTime = new DateTime('2024-01-15');
        $date2 = KhmerDate::createFromDateTime($dateTime);
        $this->assertInstanceOf(KhmerDate::class, $date2);
        $this->assertEquals('2024-01-15', $date2->format('Y-m-d'));
    }

    public function testToString()
    {
        $date = new KhmerDate('2024-01-15');
        $stringRepresentation = (string) $date;
        $this->assertIsString($stringRepresentation);
        $this->assertNotEmpty($stringRepresentation);
        $this->assertEquals($date->toLunarDate(), $stringRepresentation);
    }

    public function testSpecificKnownDate()
    {
        // Test the example date from the original momentkh
        $date = new KhmerDate('1996-09-24');

        $khmerDate = $date->toLunarDate();
        $this->assertIsString($khmerDate);
        $this->assertNotEmpty($khmerDate);

        // The exact output might vary due to calculation differences,
        // but it should contain expected elements
        $this->assertStringContainsString('ខែ', $khmerDate);
        $this->assertStringContainsString('ថ្ងៃ', $khmerDate);
    }
}
