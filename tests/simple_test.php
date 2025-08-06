<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunarDate\KhmerDate;
use PPhatDev\LunarDate\Constants;
use PPhatDev\LunarDate\KhmerCalculator;
use PPhatDev\LunarDate\Utils;

/**
 * Simple test runner for basic functionality
 */
class SimpleTestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $errors = [];

    public function assert($condition, string $message): void
    {
        if ($condition) {
            $this->passed++;
            echo "✓ $message\n";
        } else {
            $this->failed++;
            $this->errors[] = $message;
            echo "✗ $message\n";
        }
    }

    public function assertEquals($expected, $actual, string $message): void
    {
        $this->assert($expected === $actual, $message . " (expected: $expected, got: $actual)");
    }

    public function assertTrue($condition, string $message): void
    {
        $this->assert($condition === true, $message);
    }

    public function assertFalse($condition, string $message): void
    {
        $this->assert($condition === false, $message);
    }

    public function assertNotEmpty($value, string $message): void
    {
        $this->assert(!empty($value), $message);
    }

    public function assertInstanceOf(string $expected, $actual, string $message): void
    {
        $this->assert($actual instanceof $expected, $message);
    }

    public function report(): void
    {
        echo "\n=== Test Results ===\n";
        echo "Passed: {$this->passed}\n";
        echo "Failed: {$this->failed}\n";
        echo "Total: " . ($this->passed + $this->failed) . "\n";

        if (!empty($this->errors)) {
            echo "\nFailed tests:\n";
            foreach ($this->errors as $error) {
                echo "- $error\n";
            }
        }

        echo $this->failed === 0 ? "\n✅ All tests passed!\n" : "\n❌ Some tests failed.\n";
    }
}

echo "=== Lunar Date PHP Library Tests ===\n\n";

$test = new SimpleTestRunner();

// Test 1: Basic date creation
echo "Testing basic date creation...\n";
try {
    $date = new KhmerDate('2024-01-15');
    $test->assertInstanceOf(KhmerDate::class, $date, "KhmerDate creation");
    $test->assertEquals('2024-01-15', $date->format('Y-m-d'), "Date formatting");
} catch (Exception $e) {
    $test->assertTrue(false, "Basic date creation failed: " . $e->getMessage());
}

// Test 2: Current date
echo "\nTesting current date...\n";
try {
    $now = new KhmerDate();
    $test->assertInstanceOf(KhmerDate::class, $now, "Current date creation");
    $test->assertNotEmpty($now->toLunarDate(), "Lunar date conversion");
} catch (Exception $e) {
    $test->assertTrue(false, "Current date creation failed: " . $e->getMessage());
}

// Test 3: Khmer components
echo "\nTesting Khmer date components...\n";
try {
    $date = new KhmerDate('2024-01-15');
    $khDay = $date->khDay();
    $khMonth = $date->khMonth();
    $khYear = $date->khYear();

    $test->assertTrue(is_int($khDay), "khDay returns integer");
    $test->assertTrue(is_int($khMonth), "khMonth returns integer");
    $test->assertTrue(is_int($khYear), "khYear returns integer");
    $test->assertTrue($khDay >= 0 && $khDay < 30, "khDay in valid range (0-29)");
    $test->assertTrue($khMonth >= 0 && $khMonth <= 13, "khMonth in valid range (0-13)");
    $test->assertTrue($khYear > 2500, "khYear is reasonable Buddhist Era year");
} catch (Exception $e) {
    $test->assertTrue(false, "Khmer components test failed: " . $e->getMessage());
}

// Test 4: Number conversion
echo "\nTesting number conversion...\n";
try {
    $test->assertEquals('២០២៤', KhmerDate::arabicToKhmerNumber('2024'), "Arabic to Khmer: 2024");
    $test->assertEquals('៥', KhmerDate::arabicToKhmerNumber('5'), "Arabic to Khmer: 5");
    $test->assertEquals('2024', KhmerDate::khmerToArabicNumber('២០២៤'), "Khmer to Arabic: ២០២៤");
    $test->assertEquals('5', KhmerDate::khmerToArabicNumber('៥'), "Khmer to Arabic: ៥");
} catch (Exception $e) {
    $test->assertTrue(false, "Number conversion test failed: " . $e->getMessage());
}

// Test 5: Constants
echo "\nTesting constants...\n";
try {
    $test->assertTrue(array_key_exists('មិគសិរ', Constants::LUNAR_MONTHS), "Lunar month 'មិគសិរ' exists");
    $test->assertTrue(array_key_exists('ពិសាខ', Constants::LUNAR_MONTHS), "Lunar month 'ពិសាខ' exists");
    $test->assertTrue(in_array('ជូត', Constants::ANIMAL_YEARS), "Animal year 'ជូត' exists");
    $test->assertTrue(in_array('អាទិត្យ', Constants::WEEKDAYS), "Weekday 'អាទិត្យ' exists");
    $test->assertTrue(in_array('សំរឹទ្ធិស័ក', Constants::ERA_YEARS), "Era year 'សំរឹទ្ធិស័ក' exists");
} catch (Exception $e) {
    $test->assertTrue(false, "Constants test failed: " . $e->getMessage());
}

// Test 6: Calculator functions
echo "\nTesting calculator functions...\n";
try {
    $beYear = 2567;
    $bodithey = KhmerCalculator::getBodithey($beYear);
    $avoman = KhmerCalculator::getAvoman($beYear);

    $test->assertTrue(is_int($bodithey), "getBodithey returns integer");
    $test->assertTrue($bodithey >= 0 && $bodithey < 30, "Bodithey in valid range");
    $test->assertTrue(is_int($avoman), "getAvoman returns integer");
    $test->assertTrue($avoman >= 0, "Avoman is non-negative");
} catch (Exception $e) {
    $test->assertTrue(false, "Calculator functions test failed: " . $e->getMessage());
}

// Test 7: Gregorian leap year
echo "\nTesting Gregorian leap year...\n";
try {
    $test->assertTrue(KhmerCalculator::isGregorianLeap(2024), "2024 is leap year");
    $test->assertFalse(KhmerCalculator::isGregorianLeap(2023), "2023 is not leap year");
    $test->assertTrue(KhmerCalculator::isGregorianLeap(2000), "2000 is leap year");
    $test->assertFalse(KhmerCalculator::isGregorianLeap(1900), "1900 is not leap year");
} catch (Exception $e) {
    $test->assertTrue(false, "Gregorian leap year test failed: " . $e->getMessage());
}

// Test 8: Date formatting
echo "\nTesting date formatting...\n";
try {
    $date = new KhmerDate('2024-01-15');
    $defaultFormat = $date->toLunarDate();
    $customFormat = $date->toLunarDate('dN');

    $test->assertNotEmpty($defaultFormat, "Default format produces output");
    $test->assertNotEmpty($customFormat, "Custom format produces output");
} catch (Exception $e) {
    $test->assertTrue(false, "Date formatting test failed: " . $e->getMessage());
}

// Test 9: Date manipulation
echo "\nTesting date manipulation...\n";
try {
    $date = new KhmerDate('2024-01-15');
    $originalTimestamp = $date->getTimestamp();

    $date->add('1 day');
    $newTimestamp = $date->getTimestamp();
    $test->assertTrue($newTimestamp > $originalTimestamp, "Add day increases timestamp");

    $date->subtract('2 days');
    $finalTimestamp = $date->getTimestamp();
    $test->assertTrue($finalTimestamp < $originalTimestamp, "Subtract days decreases timestamp");
} catch (Exception $e) {
    $test->assertTrue(false, "Date manipulation test failed: " . $e->getMessage());
}

// Test 10: Copy functionality
echo "\nTesting copy functionality...\n";
try {
    $original = new KhmerDate('2024-01-15');
    $copy = $original->copy();

    $test->assertInstanceOf(KhmerDate::class, $copy, "Copy returns KhmerDate instance");
    $test->assertEquals($original->format('Y-m-d'), $copy->format('Y-m-d'), "Copy has same date");
    $test->assertTrue($original !== $copy, "Copy is different object");
} catch (Exception $e) {
    $test->assertTrue(false, "Copy functionality test failed: " . $e->getMessage());
}

// Test 11: Utilities
echo "\nTesting utility functions...\n";
try {
    $season = Utils::getSeason(new KhmerDate('2024-07-15'));
    $test->assertNotEmpty($season['name'], "Season name is not empty");
    $test->assertNotEmpty($season['name_en'], "Season English name is not empty");

    $beYear = Utils::convertEra(2024, 'AD', 'BE');
    $test->assertTrue($beYear > 2500, "Era conversion AD to BE produces reasonable result");
} catch (Exception $e) {
    $test->assertTrue(false, "Utility functions test failed: " . $e->getMessage());
}

// Test 12: Known date from original momentkh
echo "\nTesting known date from original momentkh...\n";
try {
    $date = new KhmerDate('1996-09-24');
    $khmerDate = $date->toLunarDate();

    $test->assertNotEmpty($khmerDate, "1996-09-24 converts to Khmer date");
    $test->assertTrue(strpos($khmerDate, 'ខែ') !== false, "Contains 'ខែ' (month)");
    $test->assertTrue(strpos($khmerDate, 'ថ្ងៃ') !== false, "Contains 'ថ្ងៃ' (day)");
} catch (Exception $e) {
    $test->assertTrue(false, "Known date test failed: " . $e->getMessage());
}

$test->report();
