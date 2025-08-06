<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunarDate\KhmerDate;

echo "Khmer New Year Examples:\n";

// Get Khmer New Year for multiple years
$years = [2025];

foreach ($years as $year) {
    try {
        $newYearMoment = KhmerDate::getKhNewYearMoment($year);

        // Show both Gregorian and Khmer date formats
        $khmerNewYear = new KhmerDate($newYearMoment);
        $khDay = $khmerNewYear->toKhmerDate();

        echo "Khmer New Year $year:\n";
        echo "  Gregorian: " . $newYearMoment->format('l, F j, Y \a\t g:i A') . "\n";
        echo "  Khmer: " . $khmerNewYear->toLunarDate() . "\n";
        echo "  Custom Format: " . $khmerNewYear->toLunarDate('ថ្ងៃW dN ខែm ឆ្នាំa e ព.ស. b') . "\n";
        echo "  Custom Format: " . $khmerNewYear->toLunarDate() . " ត្រូវនឹងថ្ងៃ" . $khDay . "\n";
        echo "\n";
    } catch (Exception $e) {
        echo "Error calculating New Year for $year: " . $e->getMessage() . "\n\n";
    }
}

// Calculate days until next Khmer New Year
$today = new DateTime();
$currentYear = (int)$today->format('Y');
$nextNewYear = KhmerDate::getKhNewYearMoment($currentYear);

// If this year's new year has passed, get next year's
if ($today > $nextNewYear) {
    $nextNewYear = KhmerDate::getKhNewYearMoment($currentYear + 1);
}

$diff = $today->diff($nextNewYear);
echo "Days until next Khmer New Year: " . $diff->days . " days\n";
echo "Next New Year: " . $nextNewYear->format('Y-m-d H:i') . "\n\n";
