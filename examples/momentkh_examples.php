<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunaDate\KhmerDate;

/**
 * Example usage similar to the original momentkh JavaScript examples
 */

echo "=== MomentKH PHP Port Examples ===\n\n";

// Example 1: Basic usage (equivalent to momentkh example)
echo "Example 1: Basic Date Conversion\n";
echo "const moment = require('moment');\n";
echo "require('@thyrith/momentkh')(moment);\n";
echo "let today = moment();\n";
echo "console.log(today.toLunarDate());\n\n";

echo "PHP Equivalent:\n";
$today = new KhmerDate();
echo "use PPhatDev\\LunaDate\\KhmerDate;\n";
echo "\$today = new KhmerDate();\n";
echo "echo \$today->toLunarDate();\n";
echo "// Output: " . $today->toLunarDate() . "\n\n";

// Example 2: Specific date (equivalent to toKhDate.js example)
echo "Example 2: Specific Date (like toKhDate.js example)\n";
echo "let today = moment('1996-09-24T00:00:00.000');\n";
echo "console.log(today.toKhDate());\n\n";

echo "PHP Equivalent:\n";
$specificDate = new KhmerDate('1996-09-24');
echo "\$date = new KhmerDate('1996-09-24');\n";
echo "echo \$date->toLunarDate();\n";
echo "// Output: " . $specificDate->toLunarDate() . "\n\n";

// Example 3: Custom formatting
echo "Example 3: Custom Formatting\n";
echo "let myBirthday = moment('4/3/1992', 'd/m/yyy');\n";
echo "myBirthday.toLunarDate('dN ថ្ងៃW ខែm ព.ស. b');\n";
echo "// ៦កើត ថ្ងៃព្រហស្បតិ៍ ខែមិគសិរ ព.ស. ២៥៦២\n\n";

echo "PHP Equivalent:\n";
$birthday = new KhmerDate('1992-03-04');
echo "\$birthday = new KhmerDate('1992-03-04');\n";
echo "echo \$birthday->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b');\n";
echo "// Output: " . $birthday->toLunarDate('dN ថ្ងៃW ខែm ព.ស. b') . "\n\n";

// Example 4: New Year moment (equivalent to newYearMoment.js example)
echo "Example 4: Khmer New Year Moment (like newYearMoment.js example)\n";
echo "console.log(moment.getKhNewYearMoment(2021));\n\n";

echo "PHP Equivalent:\n";
try {
    $newYear2021 = KhmerDate::getKhNewYearMoment(2021);
    echo "echo KhmerDate::getKhNewYearMoment(2021)->format('Y-m-d H:i');\n";
    echo "// Output: " . $newYear2021->format('Y-m-d H:i') . "\n\n";
} catch (Exception $e) {
    echo "// Error: " . $e->getMessage() . "\n\n";
}

// Example 5: Individual components
echo "Example 5: Individual Date Components\n";
echo "moment().khDay()    // Get Khmer day\n";
echo "moment().khMonth()  // Get Khmer month\n";
echo "moment().khYear()   // Get Buddhist Era year\n\n";

echo "PHP Equivalent:\n";
$now = new KhmerDate();
echo "\$now = new KhmerDate();\n";
echo "echo \$now->khDay();    // " . $now->khDay() . "\n";
echo "echo \$now->khMonth();  // " . $now->khMonth() . "\n";
echo "echo \$now->khYear();   // " . $now->khYear() . "\n\n";

// Example 6: Format tokens
echo "Example 6: Available Format Tokens\n";
echo "W  - ថ្ងៃនៃសប្ដាហ៍ (Day of week)\n";
echo "w  - ថ្ងៃនៃសប្ដាហ៍កាត់ (Day of week short)\n";
echo "d  - ថ្ងៃទី (Lunar day count)\n";
echo "D  - ថ្ងៃទី ០១-១៥ (Lunar day with leading zero)\n";
echo "n  - កើត ឬ រោច (Moon status short)\n";
echo "N  - កើត ឬ រោច (Moon status full)\n";
echo "m  - ខែចន្ទគតិ (Lunar month)\n";
echo "M  - ខែសុរិយគតិ (Solar month)\n";
echo "a  - ឆ្នាំសត្វ (Animal year)\n";
echo "e  - ស័ក (Era)\n";
echo "b  - ឆ្នាំពុទ្ធសករាជ (Buddhist Era year)\n";
echo "c  - ឆ្នាំគ្រិស្តសករាជ (Gregorian year)\n";
echo "j  - ឆ្នាំចុល្លសករាជ (Jolak Sakaraj year)\n\n";

// Example 7: Different format examples
echo "Example 7: Various Format Examples\n";
$sampleDate = new KhmerDate('2024-01-15');

$formats = [
    'W' => 'Day of week only',
    'dN' => 'Day and moon status',
    'dN ខែm' => 'Day, moon status, and month',
    'ថ្ងៃW dN ខែm ឆ្នាំa e ព.ស. b' => 'Full traditional format',
    'c-M-d' => 'Mixed Gregorian-Khmer format'
];

foreach ($formats as $format => $description) {
    echo "Format: '$format' ($description)\n";
    echo "Result: " . $sampleDate->toLunarDate($format) . "\n\n";
}

echo "=== Examples Complete ===\n";
