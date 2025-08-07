<?php
namespace PPhatDev\LunarDate;

use DateTime;
use Exception;

/**
 * Utility class with helper functions for Khmer date operations
 */
class Utils
{
    /**
     * Parse Khmer date string (basic implementation)
     * This is a simplified version - full implementation would be more complex
     *
     * @param string $khmerDateString
     * @return array|null
     */
    public static function parseKhmerDate(string $khmerDateString): ?array
    {
        // This is a basic implementation
        // A full parser would handle various Khmer date formats
        return null;
    }

    /**
     * Get the range of dates for a Khmer month
     *
     * @param int $khmerMonth
     * @param int $beYear
     * @return array
     */
    public static function getKhmerMonthRange(int $khmerMonth, int $beYear): array
    {
        $days = KhmerCalculator::getNumberOfDayInKhmerMonth($khmerMonth, $beYear);
        $range = [];

        for ($day = 0; $day < $days; $day++) {
            $moonDay = KhmerCalculator::getKhmerLunarDay($day);
            $range[] = [
                'day' => $day,
                'count' => $moonDay['count'],
                'moonStatus' => $moonDay['moonStatus'],
                'formatted' => $moonDay['count'] . ($moonDay['moonStatus'] === 0 ? 'កើត' : 'រោច')
            ];
        }

        return $range;
    }

    /**
     * Find all occurrences of a specific lunar day in a year
     *
     * @param int $dayCount Day count (1-15)
     * @param int $moonStatus Moon status (0=កើត, 1=រោច)
     * @param int $year Gregorian year
     * @return array
     */
    public static function findLunarDayOccurrences(int $dayCount, int $moonStatus, int $year): array
    {
        $occurrences = [];
        $startDate = new DateTime("$year-01-01");
        $endDate = new DateTime(($year + 1) . "-01-01");

        $current = clone $startDate;
        while ($current < $endDate) {
            $khmerDate = new KhmerDate($current);
            $lunarInfo = KhmerDate::findLunarDate($current);
            $moonDay = KhmerCalculator::getKhmerLunarDay($lunarInfo['day']);

            if ($moonDay['count'] === $dayCount && $moonDay['moonStatus'] === $moonStatus) {
                $occurrences[] = [
                    'gregorian' => $current->format('Y-m-d'),
                    'khmer' => $khmerDate->toLunarDate(),
                    'month' => $lunarInfo['month']
                ];
            }

            $current->modify('+1 day');
        }

        return $occurrences;
    }

    /**
     * Calculate the difference between two dates in Khmer calendar terms
     *
     * @param KhmerDate $date1
     * @param KhmerDate $date2
     * @return array
     */
    public static function diffInKhmer(KhmerDate $date1, KhmerDate $date2): array
    {
        $dt1 = $date1->getDateTime();
        $dt2 = $date2->getDateTime();

        $diff = $dt1->diff($dt2);

        return [
            'days' => $diff->days,
            'years' => $diff->y,
            'months' => $diff->m,
            'gregorian_diff' => $diff,
            'is_past' => $dt1 < $dt2
        ];
    }

    /**
     * Get important Buddhist holidays for a given year
     *
     * @param int $year Gregorian year
     * @return array
     */
    public static function getBuddhistHolidays(int $year): array
    {
        $holidays = [];

        try {
            // Visakha Bochea (15 កើត ពិសាខ)
            $visakhaBochea = KhmerCalculator::getVisakhaBochea($year);
            $holidays['visakha_bochea'] = [
                'name' => 'ព្រះរាជពិធីវិសាខបូជា',
                'name_en' => 'Visakha Bochea',
                'date' => $visakhaBochea->format('Y-m-d'),
                'khmer_date' => (new KhmerDate($visakhaBochea))->toLunarDate()
            ];

            // Khmer New Year
            $khmerNewYear = KhmerDate::getKhNewYearMoment($year);
            $holidays['khmer_new_year'] = [
                'name' => 'បុណ្យចូលឆ្នាំខ្មែរ',
                'name_en' => 'Khmer New Year',
                'date' => $khmerNewYear->format('Y-m-d'),
                'khmer_date' => (new KhmerDate($khmerNewYear))->toLunarDate()
            ];
        } catch (Exception $e) {
            // Handle calculation errors gracefully
        }

        return $holidays;
    }

    /**
     * Convert between different era systems
     *
     * @param int $year
     * @param string $fromEra Source era (BE, AD, JS)
     * @param string $toEra Target era (BE, AD, JS)
     * @return int
     */
    public static function convertEra(int $year, string $fromEra, string $toEra): int
    {
        // Convert to AD first
        $adYear = $year;
        switch (strtoupper($fromEra)) {
            case 'BE':
                $adYear = $year - 543; // Approximate
                break;
            case 'JS':
                $adYear = $year + 1182; // Jolak Sakaraj to AD
                break;
            case 'AD':
            default:
                $adYear = $year;
                break;
        }

        // Convert from AD to target era
        switch (strtoupper($toEra)) {
            case 'BE':
                return $adYear + 543; // Approximate
            case 'JS':
                return $adYear - 1182; // AD to Jolak Sakaraj
            case 'AD':
            default:
                return $adYear;
        }
    }

    /**
     * Validate if a date is a valid Khmer calendar date
     *
     * @param int $day
     * @param int $month
     * @param int $beYear
     * @return bool
     */
    public static function isValidKhmerDate(int $day, int $month, int $beYear): bool
    {
        // Check month bounds
        if ($month < 0 || $month > 13) {
            return false;
        }

        // Check if this is a leap month that doesn't exist this year
        if (
            ($month === Constants::LUNAR_MONTHS['បឋមាសាឍ'] || $month === Constants::LUNAR_MONTHS['ទុតិយាសាឍ'])
            && !KhmerCalculator::isKhmerLeapMonth($beYear)
        ) {
            return false;
        }

        // Check day bounds
        $maxDays = KhmerCalculator::getNumberOfDayInKhmerMonth($month, $beYear);
        if ($day < 0 || $day >= $maxDays) {
            return false;
        }

        return true;
    }

    /**
     * Get season information for a given date
     *
     * @param KhmerDate $date
     * @return array
     */
    public static function getSeason(KhmerDate $date): array
    {
        $month = $date->khMonth();

        // Traditional Khmer seasons based on lunar months
        if (in_array($month, [Constants::LUNAR_MONTHS['មិគសិរ'], Constants::LUNAR_MONTHS['បុស្ស'], Constants::LUNAR_MONTHS['មាឃ']])) {
            return ['name' => 'រដូវរងារ', 'name_en' => 'Cold Season'];
        } elseif (in_array($month, [Constants::LUNAR_MONTHS['ផល្គុន'], Constants::LUNAR_MONTHS['ចេត្រ'], Constants::LUNAR_MONTHS['ពិសាខ']])) {
            return ['name' => 'រដូវក្ដៅ', 'name_en' => 'Hot Season'];
        } else {
            return ['name' => 'រដូវវស្សា', 'name_en' => 'Rainy Season'];
        }
    }
}
