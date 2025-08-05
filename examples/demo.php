<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunaDate\KhmerDate;
use PPhatDev\LunaDate\Utils;

echo "=== Luna Date PHP Demo ===\n\n";

// Basic usage
echo "1. Basic Date Conversion:\n";
$today = new KhmerDate();
echo "Today (Gregorian): " . $today->format('Y-m-d') . "\n";
echo "Today (Khmer): " . $today->toLunarDate() . "\n\n";

// Specific date conversion
echo "2. Specific Date Conversion:\n";
$date = new KhmerDate('1996-09-24');
echo "1996-09-24 (Gregorian)\n";
echo "Khmer Date: " . $date->toLunarDate() . "\n";
echo "Custom Format: " . $date->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b') . "\n\n";

// Khmer calendar components
echo "3. Khmer Calendar Components:\n";
echo "Khmer Day: " . $date->khDay() . "\n";
echo "Khmer Month: " . $date->khMonth() . "\n";
echo "Buddhist Era Year: " . $date->khYear() . "\n\n";

// New Year calculation
echo "4. Khmer New Year:\n";
for ($year = 2023; $year <= 2025; $year++) {
    try {
        $newYear = KhmerDate::getKhNewYearMoment($year);
        echo "Khmer New Year $year: " . $newYear->format('Y-m-d H:i') . "\n";
    } catch (Exception $e) {
        echo "Error calculating New Year for $year: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Number conversion
echo "5. Number Conversion:\n";
$arabicNumber = "2024";
$khmerNumber = KhmerDate::arabicToKhmerNumber($arabicNumber);
echo "Arabic: $arabicNumber → Khmer: $khmerNumber\n";
echo "Khmer: $khmerNumber → Arabic: " . KhmerDate::khmerToArabicNumber($khmerNumber) . "\n\n";

// Calendar information
echo "6. Calendar Information:\n";
echo "Lunar Months: " . implode(', ', KhmerDate::getKhmerMonthNames()) . "\n";
echo "Animal Years: " . implode(', ', KhmerDate::getAnimalYearNames()) . "\n\n";

// Buddhist holidays
echo "7. Buddhist Holidays 2024:\n";
$holidays = Utils::getBuddhistHolidays(2024);
foreach ($holidays as $key => $holiday) {
    echo "{$holiday['name']} ({$holiday['name_en']}): {$holiday['date']}\n";
    echo "   Khmer Date: {$holiday['khmer_date']}\n";
}
echo "\n";

// Season information
echo "8. Season Information:\n";
$testDates = [
    '2024-01-15' => 'Winter',
    '2024-04-15' => 'Summer',
    '2024-07-15' => 'Rainy',
    '2024-10-15' => 'Cool'
];

foreach ($testDates as $dateStr => $expectedSeason) {
    $khDate = new KhmerDate($dateStr);
    $season = Utils::getSeason($khDate);
    echo "$dateStr: {$season['name']} ({$season['name_en']})\n";
}
echo "\n";

// Era conversion
echo "9. Era Conversion:\n";
$currentYear = 2024;
echo "Gregorian $currentYear:\n";
echo "  → Buddhist Era: " . Utils::convertEra($currentYear, 'AD', 'BE') . "\n";
echo "  → Jolak Sakaraj: " . Utils::convertEra($currentYear, 'AD', 'JS') . "\n\n";

echo "=== Demo Complete ===\n";
