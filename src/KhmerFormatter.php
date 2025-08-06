<?php

namespace PPhatDev\LunarDate;

use DateTime;
use InvalidArgumentException;

class KhmerFormatter
{
    private const KHMER_DIGITS = [
        '0' => '០',
        '1' => '១',
        '2' => '២',
        '3' => '៣',
        '4' => '៤',
        '5' => '៥',
        '6' => '៦',
        '7' => '៧',
        '8' => '៨',
        '9' => '៩'
    ];

    private const KHMER_MONTHS = [
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

    private const KHMER_DAYS = [
        'អាទិត្យ',
        'ចន្ទ',
        'អង្គារ',
        'ពុធ',
        'ព្រហស្បតិ៍',
        'សុក្រ',
        'សៅរ៍'
    ];

    private const LUNAR_MONTHS = [
        "មិគសិរ",
        "បុស្ស",
        "មាឃ",
        "ផល្គុន",
        "ចេត្រ",
        "ពិសាខ",
        "ជេស្ឋ",
        "អាសាឍ",
        "ស្រាពណ៍",
        "ភទ្របទ",
        "អស្សុជ",
        "កក្ដិក",
        "បឋមាសាឍ",
        "ទុតិយាសាឍ",
    ];

    /**
     * Convert Arabic numerals to Khmer numerals
     */
    public function toKhmerNumber(string $number): string
    {
        return strtr($number, self::KHMER_DIGITS);
    }

    /**
     * Convert Khmer numerals to Arabic numerals
     */
    public function fromKhmerNumber(string $khmerNumber): string
    {
        return strtr($khmerNumber, array_flip(self::KHMER_DIGITS));
    }

    /**
     * Format number with Khmer separators
     */
    public function formatNumber(float $number, int $decimals = 0, string $thousandsSep = ','): string
    {
        $formatted = number_format($number, $decimals, '.', $thousandsSep);
        return $this->toKhmerNumber($formatted);
    }

    /**
     * Format date in Khmer (Gregorian calendar)
     */
    public function formatDate(DateTime $date, string $format = 'full'): string
    {
        $day = $date->format('j');
        $month = (int)$date->format('n');
        $year = $date->format('Y');
        $dayOfWeek = (int)$date->format('w');

        switch ($format) {
            case 'full':
                return sprintf(
                    'ថ្ងៃ%s ទី%s ខែ%s ឆ្នាំ%s',
                    self::KHMER_DAYS[$dayOfWeek],
                    $this->toKhmerNumber($day),
                    self::KHMER_MONTHS[$month],
                    $this->toKhmerNumber($year)
                );

            case 'short':
                return sprintf(
                    '%s/%s/%s',
                    $this->toKhmerNumber($day),
                    $this->toKhmerNumber($month),
                    $this->toKhmerNumber($year)
                );

            case 'medium':
                return sprintf(
                    'ទី%s ខែ%s ឆ្នាំ%s',
                    $this->toKhmerNumber($day),
                    self::KHMER_MONTHS[$month],
                    $this->toKhmerNumber($year)
                );

            default:
                throw new InvalidArgumentException("Invalid date format: $format");
        }
    }

    /**
     * Format lunar date in Khmer
     */
    public function formatLunarDate(array $lunarData, string $format = 'full'): string
    {
        $day = $lunarData['day'];
        $month = $lunarData['month'];
        $dateTime = $lunarData['dateTime'];

        $dayOfWeek = (int)$dateTime->format('w');

        // Handle predefined formats first
        switch ($format) {
            case 'full':
                return $this->getFullLunarFormat($day, $month, $dateTime, $dayOfWeek);
            case 'short':
                return $this->getShortLunarFormat($day, $month, $dateTime);
            case 'medium':
                return $this->getMediumLunarFormat($day, $month, $dateTime);
            default:
                // Handle custom token-based formatting
                return $this->parseCustomFormat($format, $day, $month, $dateTime, $dayOfWeek);
        }
    }

    /**
     * Parse custom format string with tokens
     */
    private function parseCustomFormat(string $format, int $day, int $month, DateTime $dateTime, int $dayOfWeek): string
    {
        $moonDay = KhmerCalculator::getKhmerLunarDay($day);
        $beYear = KhmerCalculator::getBEYear($dateTime);

        $tokens = [
            'W' => self::KHMER_DAYS[$dayOfWeek], // Day of week
            'w' => mb_substr(self::KHMER_DAYS[$dayOfWeek], 0, 1), // Day of week short
            'd' => $this->toKhmerNumber((string)$moonDay['count']), // Lunar day count
            'D' => $this->toKhmerNumber(sprintf('%02d', $moonDay['count'])), // Lunar day with leading zero
            'n' => $moonDay['moonStatus'] === 0 ? 'ក' : 'រ', // Moon status short
            'N' => $moonDay['moonStatus'] === 0 ? 'កើត' : 'រោច', // Moon status full
            'm' => self::LUNAR_MONTHS[$month] ?? '', // Lunar month
            'M' => self::KHMER_MONTHS[(int)$dateTime->format('n')] ?? '', // Solar month
            'a' => $this->getAnimalYear($beYear), // Animal year
            'e' => $this->getEraYear($beYear), // Era year
            'b' => $this->toKhmerNumber((string)$beYear), // Buddhist Era year
            'c' => $this->toKhmerNumber($dateTime->format('Y')), // Gregorian year
            'j' => $this->toKhmerNumber((string)KhmerCalculator::getJolakSakarajYear($dateTime)), // Jolak Sakaraj year
        ];

        // Replace tokens in format string
        $result = $format;
        foreach ($tokens as $token => $value) {
            $result = str_replace($token, $value, $result);
        }

        return $result;
    }

    /**
     * Get animal year name
     */
    private function getAnimalYear(int $beYear): string
    {
        $animalYears = Constants::ANIMAL_YEARS;
        $index = ($beYear - 1) % 12;
        return $animalYears[$index] ?? '';
    }

    /**
     * Get era year name
     */
    private function getEraYear(int $beYear): string
    {
        $eraYears = Constants::ERA_YEARS;
        $index = ($beYear - 1) % 10;
        return $eraYears[$index] ?? '';
    }

    /**
     * Get full lunar format
     */
    private function getFullLunarFormat(int $day, int $month, DateTime $dateTime, int $dayOfWeek): string
    {
        $moonDay = KhmerCalculator::getKhmerLunarDay($day);
        $beYear = KhmerCalculator::getBEYear($dateTime);
        $animalYear = KhmerCalculator::getAnimalYear($dateTime);
        $eraYears = KhmerCalculator::getJolakSakarajYear($dateTime) % 10;

        return sprintf(
            'ថ្ងៃ%s %s%s ខែ%s ឆ្នាំ%s %s ពុទ្ធសករាជ %s',
            self::KHMER_DAYS[$dayOfWeek],
            $this->toKhmerNumber((string)$moonDay['count']),
            $moonDay['moonStatus'] === 0 ? 'កើត' : 'រោច',
            self::LUNAR_MONTHS[$month] ?? '',
            Constants::ANIMAL_YEARS[$animalYear] ?? '',
            Constants::ERA_YEARS[$eraYears] ?? '',
            $this->toKhmerNumber((string)$beYear)
        );
    }

    /**
     * Get short lunar format
     */
    private function getShortLunarFormat(int $day, int $month, DateTime $dateTime): string
    {
        $moonDay = KhmerCalculator::getKhmerLunarDay($day);

        return sprintf(
            '%s%s ខែ%s',
            $this->toKhmerNumber((string)$moonDay['count']),
            $moonDay['moonStatus'] === 0 ? 'កើត' : 'រោច',
            self::LUNAR_MONTHS[$month] ?? ''
        );
    }

    /**
     * Get medium lunar format
     */
    private function getMediumLunarFormat(int $day, int $month, DateTime $dateTime): string
    {
        $moonDay = KhmerCalculator::getKhmerLunarDay($day);
        $beYear = KhmerCalculator::getBEYear($dateTime);

        return sprintf(
            '%s%s ខែ%s ព.ស. %s',
            $this->toKhmerNumber((string)$moonDay['count']),
            $moonDay['moonStatus'] === 0 ? 'កើត' : 'រោច',
            self::LUNAR_MONTHS[$month] ?? '',
            $this->toKhmerNumber((string)$beYear)
        );
    }

    /**
     * Format currency in Cambodian Riel
     */
    public function formatCurrency(float $amount, bool $showSymbol = true): string
    {
        $formatted = $this->formatNumber($amount, 0, ',');
        return $showSymbol ? $formatted . ' រៀល' : $formatted;
    }

    /**
     * Format time in Khmer
     */
    public function formatTime(DateTime $time, bool $use24Hour = false): string
    {
        if ($use24Hour) {
            return sprintf(
                '%sម៉ោង%sនាទី',
                $this->toKhmerNumber($time->format('H')),
                $this->toKhmerNumber($time->format('i'))
            );
        }

        $hour = (int)$time->format('g');
        $minute = $time->format('i');
        $ampm = $time->format('A') === 'AM' ? 'ព្រឹក' : 'ល្ងាច';

        return sprintf(
            '%sម៉ោង%sនាទី%s',
            $this->toKhmerNumber((string)$hour),
            $this->toKhmerNumber($minute),
            $ampm
        );
    }

    /**
     * Get day name in Khmer
     */
    public function getDayName(DateTime $date): string
    {
        $dayOfWeek = (int)$date->format('w');
        return self::KHMER_DAYS[$dayOfWeek];
    }

    /**
     * Get month name in Khmer
     */
    public function getMonthName(DateTime $date): string
    {
        $month = (int)$date->format('n');
        return self::KHMER_MONTHS[$month];
    }

    /**
     * Get lunar month name
     */
    public function getLunarMonthName(int $monthIndex): string
    {
        return self::LUNAR_MONTHS[$monthIndex] ?? 'អញ្ញាត';
    }

    /**
     * Check if text contains Khmer characters
     */
    public function isKhmerText(string $text): bool
    {
        return preg_match('/[\x{1780}-\x{17FF}]/u', $text) === 1;
    }

    /**
     * Format ordinal numbers in Khmer
     */
    public function formatOrdinal(int $number): string
    {
        return 'ទី' . $this->toKhmerNumber((string)$number);
    }

    /**
     * Static format method for lunar dates (used by KhmerDate class)
     */
    public static function format(array $lunarData, ?string $format = null): string
    {
        $formatter = new self();
        return $formatter->formatLunarDate($lunarData, $format ?? 'full');
    }
}
