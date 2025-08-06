<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunarDate\KhmerDate;
use PPhatDev\LunarDate\Utils;

echo "=== Luna Date PHP Demo ===\n\n";

// Basic usage
echo "1. Basic Date Conversion:\n";
$today = new KhmerDate();
echo "\tToday (Gregorian): {$today->format('Y-m-d')}\n";
echo "\tToday (Khmer Lunar): {$today->toLunarDate()}\n";
echo "\tToday (Khmer Gregorian): ថ្ងៃ{$today->toKhmerDate()}\n\n\n";


// Specific date conversion
echo "2. Specific Date Conversion:\n";
$date = new KhmerDate('2024-10-10');
echo "\t2024-10-10 (Gregorian)\n";
echo "\tKhmer Date: " . $date->toLunarDate() . "\n";
echo "\tCustom Format: " . $date->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b') . "\n";
echo "\tKhmer Gregorian Date: " . $date->toKhmerDate() . "\n\n\n";


// Khmer calendar components
$date = new KhmerDate();
echo "3. Khmer Calendar {$date->format('Y')} Components:\n";
echo "\tKhmer Day: " . $date->khDay() . "\n";
echo "\tKhmer Month: " . $date->khMonth() . "\n";
echo "\tBuddhist Era Year: " . $date->khYear() . "\n\n\n";



// New Year calculation
echo "4. Khmer New Year:\n";
for ($year = 2023; $year <= 2025; $year++) {
    try {
        $newYear = KhmerDate::getKhNewYearMoment($year);
        echo "\tKhmer New Year $year: " . $newYear->format('Y-m-d H:i') . "\n";
    } catch (Exception $e) {
        echo "\033[31m\tError calculating New Year for $year: " . $e->getMessage() . "\033[0m\n";
    }
}
echo "\n\n";


// Number conversion
echo "5. Number Conversion:\n";
$data = new KhmerDate();
$arabicNumber = $data->format('Y'); // Current year as Arabic number
$khmerNumber = KhmerDate::arabicToKhmerNumber($arabicNumber);
echo "\tArabic: $arabicNumber → Khmer: $khmerNumber\n";
echo "\tKhmer: $khmerNumber → Arabic: " . KhmerDate::khmerToArabicNumber($khmerNumber) . "\n\n\n";



// Calendar information
echo "6. Calendar Information:\n";
echo "\tLunar Months: " . implode(', ', KhmerDate::getKhmerMonthNames()) . "\n";
echo "\tAnimal Years: " . implode(', ', KhmerDate::getAnimalYearNames()) . "\n\n\n";



// Buddhist holidays
$date = new KhmerDate();
echo "7. Buddhist Holidays {$date->format('Y')}:\n";
$holidays = Utils::getBuddhistHolidays($date->format('Y'));
foreach ($holidays as $key => $holiday) {
    echo "\t{$holiday['name']} ({$holiday['name_en']}): {$holiday['date']}\n";
    echo "\tKhmer Date: {$holiday['khmer_date']}\n";
}
echo "\n\n";



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
    echo "\t$dateStr: {$season['name']} ({$season['name_en']})\n";
}
echo "\n\n\n";

// Era conversion
$date = new KhmerDate();
$currentYear = $date->format('Y');
echo "9. Era Conversion Gregorian $currentYear:\n";
echo "\tBuddhist Era (B.E.): " . Utils::convertEra($currentYear, 'AD', 'BE') . "\n";
echo "\tJolak Sakaraj (J.S.): " . Utils::convertEra($currentYear, 'AD', 'JS') . "\n\n";

echo "=== Demo Complete ===\n";
