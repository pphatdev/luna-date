<?php

namespace PPhatDev\LunarDate;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Main class for Khmer date conversion and formatting
 * Ported from momentkh JavaScript library
 */
class KhmerDate
{
    protected DateTime $dateTime;
    protected static array $khNewYearCache = [];

    public function __construct($date = null, ?DateTimeZone $timezone = null)
    {
        if ($date === null) {
            $this->dateTime = new DateTime('now', $timezone);
        } elseif ($date instanceof DateTime) {
            $this->dateTime = clone $date;
            if ($timezone) {
                $this->dateTime->setTimezone($timezone);
            }
        } elseif (is_string($date)) {
            $this->dateTime = new DateTime($date, $timezone);
        } elseif (is_int($date)) {
            $this->dateTime = new DateTime();
            $this->dateTime->setTimestamp($date);
            if ($timezone) {
                $this->dateTime->setTimezone($timezone);
            }
        } else {
            throw new InvalidArgumentException('Invalid date input');
        }
    }

    /**
     * Create new instance
     */
    public static function create($date = null, ?DateTimeZone $timezone = null): self
    {
        return new self($date, $timezone);
    }

    /**
     * Create from DateTime
     */
    public static function createFromDateTime(DateTime $dateTime): self
    {
        return new self($dateTime);
    }

    /**
     * Get the underlying DateTime object
     */
    public function getDateTime(): DateTime
    {
        return clone $this->dateTime;
    }

    /**
     * Find lunar date from Gregorian date
     * Core conversion algorithm ported from momentkh
     *
     * @param DateTime $target Target date
     * @return array ['day' => int, 'month' => int, 'epochMoved' => DateTime]
     */
    public static function findLunarDate(DateTime $target): array
    {
        // Epoch Date: January 1, 1900
        $epochMoment = new DateTime('1900-01-01');
        $khmerMonth = Constants::LUNAR_MONTHS['បុស្ស'];
        $khmerDay = 0; // 0 - 29 ១កើត ... ១៥កើត ១រោច ...១៤រោច (១៥រោច)

        $differentFromEpoch = $target->getTimestamp() - $epochMoment->getTimestamp();

        // Find nearest year epoch
        if ($differentFromEpoch > 0) {
            while (
                ($target->getTimestamp() - $epochMoment->getTimestamp()) / 86400 >
                KhmerCalculator::getNumberOfDayInKhmerYear(
                    KhmerCalculator::getMaybeBEYear((clone $epochMoment)->modify('+1 year'))
                )
            ) {
                $epochMoment->modify('+' . KhmerCalculator::getNumberOfDayInKhmerYear(
                    KhmerCalculator::getMaybeBEYear((clone $epochMoment)->modify('+1 year'))
                ) . ' days');
            }
        } else {
            do {
                $epochMoment->modify('-' . KhmerCalculator::getNumberOfDayInKhmerYear(
                    KhmerCalculator::getMaybeBEYear($epochMoment)
                ) . ' days');
            } while (($epochMoment->getTimestamp() - $target->getTimestamp()) / 86400 > 0);
        }

        // Move epoch month
        while (
            ($target->getTimestamp() - $epochMoment->getTimestamp()) / 86400 >
            KhmerCalculator::getNumberOfDayInKhmerMonth($khmerMonth, KhmerCalculator::getMaybeBEYear($epochMoment))
        ) {
            $epochMoment->modify('+' . KhmerCalculator::getNumberOfDayInKhmerMonth(
                $khmerMonth,
                KhmerCalculator::getMaybeBEYear($epochMoment)
            ) . ' days');
            $khmerMonth = KhmerCalculator::nextMonthOf($khmerMonth, KhmerCalculator::getMaybeBEYear($epochMoment));
        }

        $khmerDay += floor(($target->getTimestamp() - $epochMoment->getTimestamp()) / 86400);

        // Fix result display 15 រោច when it should be next month
        $totalDaysOfTheMonth = KhmerCalculator::getNumberOfDayInKhmerMonth($khmerMonth, KhmerCalculator::getMaybeBEYear($target));
        if ($totalDaysOfTheMonth <= $khmerDay) {
            $khmerDay = $khmerDay % $totalDaysOfTheMonth;
            $khmerMonth = KhmerCalculator::nextMonthOf($khmerMonth, KhmerCalculator::getMaybeBEYear($epochMoment));
        }

        $epochMoment->modify('+' . floor(($target->getTimestamp() - $epochMoment->getTimestamp()) / 86400) . ' days');

        return [
            'day' => (int)$khmerDay,
            'month' => $khmerMonth,
            'epochMoved' => $epochMoment
        ];
    }

    /**
     * Get Khmer New Year moment for a given Gregorian year
     *
     * @param int $gregorianYear
     * @return DateTime
     */
    public static function getKhNewYearMoment(int $gregorianYear): DateTime
    {
        // Check cache first
        if (isset(self::$khNewYearCache[$gregorianYear])) {
            return clone self::$khNewYearCache[$gregorianYear];
        }

        // Check predefined moments
        if (isset(Constants::KH_NEW_YEAR_MOMENTS[$gregorianYear])) {
            $dateTime = DateTime::createFromFormat('d-m-Y H:i', Constants::KH_NEW_YEAR_MOMENTS[$gregorianYear]);
            self::$khNewYearCache[$gregorianYear] = $dateTime;
            return clone $dateTime;
        }

        // Calculate using Soriyatra Lerng Sak
        $jsYear = ($gregorianYear + 544) - 1182;
        $info = SoriyatraLerngSak::calculate($jsYear);

        // Number of New Year days
        $numberNewYearDay = $info['newYearsDaySotins'][0]['angsar'] === 0 ? 4 : 3;

        $epochLerngSak = DateTime::createFromFormat(
            'd-m-Y H:i',
            "17-04-$gregorianYear {$info['timeOfNewYear']['hour']}:{$info['timeOfNewYear']['minute']}"
        );

        $khEpoch = self::findLunarDate($epochLerngSak);
        $diffFromEpoch = ((($khEpoch['month'] - 4) * 29) + $khEpoch['day']) -
            ((($info['lunarDateLerngSak']['month'] - 4) * 29) + $info['lunarDateLerngSak']['day']);

        $result = clone $epochLerngSak;
        $result->modify('-' . ($diffFromEpoch + $numberNewYearDay - 1) . ' days');

        // Cache the result
        self::$khNewYearCache[$gregorianYear] = clone $result;

        return $result;
    }

    /**
     * Convert to Khmer lunar date format
     *
     * @param string|null $format Format string
     * @return string
     */
    public function toLunarDate(?string $format = null): string
    {
        $lunarDate = self::findLunarDate($this->dateTime);

        return KhmerFormatter::format([
            'day' => $lunarDate['day'],
            'month' => $lunarDate['month'],
            'dateTime' => $this->dateTime
        ], $format);
    }

    /**
     * Get Khmer day (0-29)
     *
     * @return int
     */
    public function khDay(): int
    {
        $result = self::findLunarDate($this->dateTime);
        return $result['day'];
    }

    /**
     * Get Khmer month index
     *
     * @return int
     */
    public function khMonth(): int
    {
        $result = self::findLunarDate($this->dateTime);
        return $result['month'];
    }

    /**
     * Get Buddhist Era year
     *
     * @return int
     */
    public function khYear(): int
    {
        return KhmerCalculator::getBEYear($this->dateTime);
    }


    /**
     * Get Khmer Date formatted with customizable format
     * @example: "ទី១៥ ខែមិថុនា ឆ្នាំ២០២៥"
     * @param string|null $format Example: "ទី{day} ខែ{month} ឆ្នាំ{year}"
     * @return string
     */
    public function toKhmerDate(?string $format = null): string
    {
        $dateTime = $this->dateTime;
        $formatter = new KhmerFormatter();

        // Default format if none provided
        if ($format === null) {
            $format = "ទី{day} ខែ{month} ឆ្នាំ{year}";
        }

        // Replace placeholders with actual values
        return strtr($format, [
            '{day}' => $formatter->toKhmerNumber($dateTime->format('d')),
            '{month}' => array_keys(Constants::SOLAR_MONTHS)[$dateTime->format('m') - 1] ?? '',
            '{year}' => $formatter->toKhmerNumber($dateTime->format('Y')),
            '{dayOfWeek}' => $formatter->toKhmerNumber($dateTime->format('w')),
            '{dayOfWeekKhmer}' => Constants::WEEKDAYS[$dateTime->format('w')] ?? '',
            '{dayOfWeekShort}' => Constants::WEEKDAYS_SHORT[$dateTime->format('w')] ?? '',
        ]);
    }

    /**
     * Format using PHP's DateTime format
     *
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {
        return $this->dateTime->format($format);
    }

    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->dateTime->getTimestamp();
    }

    /**
     * Add time to the date
     *
     * @param string $interval
     * @return self
     */
    public function add(string $interval): self
    {
        $this->dateTime->modify("+$interval");
        return $this;
    }

    /**
     * Subtract time from the date
     *
     * @param string $interval
     * @return self
     */
    public function subtract(string $interval): self
    {
        $this->dateTime->modify("-$interval");
        return $this;
    }

    /**
     * Clone the instance
     *
     * @return self
     */
    public function copy(): self
    {
        return new self($this->dateTime);
    }

    /**
     * Convert to string (default Khmer format)
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toLunarDate();
    }

    /**
     * Get array of Khmer month names
     *
     * @return array
     */
    public static function getKhmerMonthNames(): array
    {
        return array_keys(Constants::LUNAR_MONTHS);
    }

    /**
     * Get array of animal year names
     *
     * @return array
     */
    public static function getAnimalYearNames(): array
    {
        return Constants::ANIMAL_YEARS;
    }

    /**
     * Get array of era year names
     *
     * @return array
     */
    public static function getEraYearNames(): array
    {
        return Constants::ERA_YEARS;
    }

    /**
     * Convert Khmer numbers to Arabic numbers
     *
     * @param string $khmerNumber
     * @return string
     */
    public static function khmerToArabicNumber(string $khmerNumber): string
    {
        return strtr($khmerNumber, Constants::ARABIC_NUMBERS);
    }

    /**
     * Convert Arabic numbers to Khmer numbers
     *
     * @param string $arabicNumber
     * @return string
     */
    public static function arabicToKhmerNumber(string $arabicNumber): string
    {
        return strtr($arabicNumber, Constants::KHMER_NUMBERS);
    }

    /**
     * Arabic to Khmer number conversion
     * * @param int $number
     * @return string
     * Converts Arabic numerals to Khmer numerals using the mapping defined in Constants.
     */
    public static function getKhmerNumber(int $number): string
    {
        return self::arabicToKhmerNumber((string)$number);
    }
}
