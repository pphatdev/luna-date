<?php
namespace PPhatDev\LunarDate;

use DateTime;
use InvalidArgumentException;
use RuntimeException;

/**
 * Core calculations for Khmer calendar system
 *
 * This class provides methods for calculating various aspects of the Khmer calendar,
 * including leap years, lunar phases, and date conversions.
 * Ported from momentkh JavaScript library.
 *
 * @author PPhatDev
 * @package PPhatDev\LunarDate
 * @since 1.0.0
 */
final class KhmerCalculator
{
    /**
     * Calculate Bodithey (បូតិថី)
     *
     * Determines if a given beYear is a leap-month year.
     * Bodithey is a calculation used in the Khmer calendar to determine
     * whether a year should have an additional month.
     *
     * @param int $beYear Buddhist Era year (must be positive)
     * @return int Number between 0-29 representing the Bodithey value
     * @throws InvalidArgumentException If the year is negative
     */
    public static function getBodithey(int $beYear): int
    {
        if ($beYear < 0) {
            throw new InvalidArgumentException('Buddhist Era year must be positive');
        }

        $ahk = self::getAharkun($beYear);
        $avml = intval((11 * $ahk + 25) / 692);
        $m = $avml + $ahk + 29;
        return $m % 30;
    }

    /**
     * Calculate Avoman (អាវមាន)
     *
     * Determines if a given year is a leap-day year.
     * Avoman is used in the Khmer calendar to calculate whether
     * an additional day should be added to certain months.
     *
     * @param int $beYear Buddhist Era year (0-691, cyclic)
     * @return int Avoman value (0-691)
     * @throws InvalidArgumentException If the year is negative
     */
    public static function getAvoman(int $beYear): int
    {
        if ($beYear < 0) {
            throw new InvalidArgumentException('Buddhist Era year must be positive');
        }

        $ahk = self::getAharkun($beYear);
        $avm = (11 * $ahk + 25) % 692;
        return $avm;
    }

    /**
     * Calculate Aharkun (អាហារគុណ ឬ ហារគុណ)
     *
     * Used for Avoman and Bodithey calculation. This is a fundamental
     * calculation in the Khmer calendar system for determining lunar cycles.
     *
     * @param int $beYear Buddhist Era year
     * @return int Aharkun value
     * @throws InvalidArgumentException If the year is negative
     */
    public static function getAharkun(int $beYear): int
    {
        if ($beYear < 0) {
            throw new InvalidArgumentException('Buddhist Era year must be positive');
        }

        $t = $beYear * 292207 + 499;
        $ahk = intval($t / 800) + 4;
        return $ahk;
    }

    /**
     * Calculate Kromathupul
     *
     * @param int $beYear Buddhist Era year
     * @return int (1-800)
     */
    public static function kromthupul(int $beYear): int
    {
        $ah = self::getAharkunMod($beYear);
        $krom = 800 - $ah;
        return $krom;
    }

    /**
     * Check if Khmer solar leap year
     *
     * @param int $beYear Buddhist Era year
     * @return int
     */
    public static function isKhmerSolarLeap(int $beYear): int
    {
        $krom = self::kromthupul($beYear);
        return $krom <= 207 ? 1 : 0;
    }

    /**
     * Get Aharkun Mod
     *
     * @param int $beYear Buddhist Era year
     * @return int
     */
    public static function getAharkunMod(int $beYear): int
    {
        $t = $beYear * 292207 + 499;
        $ahkmod = $t % 800;
        return $ahkmod;
    }

    /**
     * Calculate Bodithey leap type
     * Returns: 0=regular, 1=leap month, 2=leap day, 3=leap day and month
     *
     * @param int $beYear Buddhist Era year
     * @return int
     */
    public static function getBoditheyLeap(int $beYear): int
    {
        $result = 0;
        $avoman = self::getAvoman($beYear);
        $bodithey = self::getBodithey($beYear);

        // Check bodithey leap month
        $boditheyLeap = 0;
        if ($bodithey >= 25 || $bodithey <= 5) {
            $boditheyLeap = 1;
        }

        // Check for avoman leap-day based on gregorian leap
        $avomanLeap = 0;
        if (self::isKhmerSolarLeap($beYear)) {
            if ($avoman <= 126) {
                $avomanLeap = 1;
            }
        } else {
            if ($avoman <= 137) {
                // Check for avoman case 137/0, 137 must be normal year
                if (self::getAvoman($beYear + 1) === 0) {
                    $avomanLeap = 0;
                } else {
                    $avomanLeap = 1;
                }
            }
        }

        // Case of 25/5 consecutively, only bodithey 5 can be leap-month, so set bodithey 25 to none
        if ($bodithey === 25) {
            $nextBodithey = self::getBodithey($beYear + 1);
            if ($nextBodithey === 5) {
                $boditheyLeap = 0;
            }
        }

        // Case of 24/6 consecutively, 24 must be leap-month
        if ($bodithey === 24) {
            $nextBodithey = self::getBodithey($beYear + 1);
            if ($nextBodithey === 6) {
                $boditheyLeap = 1;
            }
        }

        // Format leap result (0:regular, 1:month, 2:day, 3:both)
        if ($boditheyLeap === 1 && $avomanLeap === 1) {
            $result = 3;
        } elseif ($boditheyLeap === 1) {
            $result = 1;
        } elseif ($avomanLeap === 1) {
            $result = 2;
        } else {
            $result = 0;
        }

        return $result;
    }

    /**
     * Get Protetin leap type (final leap calculation)
     * Returns: 0=regular, 1=leap month, 2=leap day
     *
     * @param int $beYear Buddhist Era year
     * @return int
     */
    public static function getProtetinLeap(int $beYear): int
    {
        $b = self::getBoditheyLeap($beYear);
        if ($b === 3) {
            return 1;
        }
        if ($b === 2 || $b === 1) {
            return $b;
        }
        // Case of previous year is 3
        if (self::getBoditheyLeap($beYear - 1) === 3) {
            return 2;
        }
        // Normal case
        return 0;
    }

    /**
     * Check if Khmer leap month year
     *
     * @param int $beYear Buddhist Era year
     * @return bool
     */
    public static function isKhmerLeapMonth(int $beYear): bool
    {
        return self::getProtetinLeap($beYear) === 1;
    }

    /**
     * Check if Khmer leap day year
     *
     * @param int $beYear Buddhist Era year
     * @return bool
     */
    public static function isKhmerLeapDay(int $beYear): bool
    {
        return self::getProtetinLeap($beYear) === 2;
    }

    /**
     * Check if Gregorian leap year
     *
     * @param int $adYear Gregorian year
     * @return bool
     */
    public static function isGregorianLeap(int $adYear): bool
    {
        return ($adYear % 4 === 0 && $adYear % 100 !== 0) || ($adYear % 400 === 0);
    }

    /**
     * Get number of days in Khmer month
     *
     * Calculates the number of days in a specific Khmer lunar month,
     * taking into account leap days and special month rules.
     *
     * @param int $beMonth Khmer month index (0-13)
     * @param int $beYear Buddhist Era year
     * @return int Number of days in the month (29 or 30)
     * @throws InvalidArgumentException If month or year is invalid
     */
    public static function getNumberOfDayInKhmerMonth(int $beMonth, int $beYear): int
    {
        if ($beYear < 0) {
            throw new InvalidArgumentException('Buddhist Era year must be positive');
        }

        $validMonths = array_values(Constants::LUNAR_MONTHS);
        if (!in_array($beMonth, $validMonths, true)) {
            throw new InvalidArgumentException("Invalid Khmer month index: $beMonth");
        }

        if ($beMonth === Constants::LUNAR_MONTHS['ជេស្ឋ'] && self::isKhmerLeapDay($beYear)) {
            return 30;
        }
        if ($beMonth === Constants::LUNAR_MONTHS['បឋមាសាឍ'] || $beMonth === Constants::LUNAR_MONTHS['ទុតិយាសាឍ']) {
            return 30;
        }
        // មិគសិរ : 29 , បុស្ស : 30 , មាឃ : 29 .. 30 .. 29 ..30 .....
        return $beMonth % 2 === 0 ? 29 : 30;
    }

    /**
     * Get number of days in Khmer year
     *
     * @param int $beYear Buddhist Era year
     * @return int
     */
    public static function getNumberOfDayInKhmerYear(int $beYear): int
    {
        if (self::isKhmerLeapMonth($beYear)) {
            return 384;
        } elseif (self::isKhmerLeapDay($beYear)) {
            return 355;
        } else {
            return 354;
        }
    }

    /**
     * Get number of days in Gregorian year
     *
     * @param int $adYear Gregorian year
     * @return int
     */
    public static function getNumberOfDayInGregorianYear(int $adYear): int
    {
        return self::isGregorianLeap($adYear) ? 366 : 365;
    }

    /**
     * Get Buddhist Era year from DateTime
     *
     * @param DateTime $dateTime The date to convert
     * @return int The Buddhist Era year
     * @throws RuntimeException If Visakha Bochea cannot be found
     */
    public static function getBEYear(DateTime $dateTime): int
    {
        $visakhaBochea = self::getVisakhaBochea((int)$dateTime->format('Y'));
        if ($dateTime >= $visakhaBochea) {
            return (int)$dateTime->format('Y') + 544;
        } else {
            return (int)$dateTime->format('Y') + 543;
        }
    }

    /**
     * Get maybe Buddhist Era year (for calculation purposes)
     *
     * @param DateTime $dateTime
     * @return int
     */
    public static function getMaybeBEYear(DateTime $dateTime): int
    {
        if ((int)$dateTime->format('n') <= Constants::SOLAR_MONTHS['មេសា'] + 1) {
            return (int)$dateTime->format('Y') + 543;
        } else {
            return (int)$dateTime->format('Y') + 544;
        }
    }

    /**
     * Find Visakha Bochea day (end of Buddhist year)
     *
     * Visakha Bochea marks the end of the Buddhist year and is calculated
     * based on the lunar calendar (14th day of Visakha month).
     *
     * @param int $gregorianYear The Gregorian year to find Visakha Bochea in
     * @return DateTime The date of Visakha Bochea
     * @throws RuntimeException If Visakha Bochea cannot be found
     * @throws InvalidArgumentException If the year is invalid
     */
    public static function getVisakhaBochea(int $gregorianYear): DateTime
    {
        if ($gregorianYear < 1) {
            throw new InvalidArgumentException('Gregorian year must be positive');
        }

        $date = new DateTime("$gregorianYear-01-01");
        for ($i = 0; $i < 365; $i++) {
            $lunarDate = KhmerDate::findLunarDate($date);
            if ($lunarDate['month'] === Constants::LUNAR_MONTHS['ពិសាខ'] && $lunarDate['day'] === 14) {
                return $date;
            }
            $date->modify('+1 day');
        }
        throw new RuntimeException("Cannot find Visakha Bochea day for year $gregorianYear. Please report this bug.");
    }

    /**
     * Get Jolak Sakaraj year
     *
     * @param DateTime $dateTime
     * @return int
     */
    public static function getJolakSakarajYear(DateTime $dateTime): int
    {
        $gregorianYear = (int)$dateTime->format('Y');
        $newYearMoment = KhmerDate::getKhNewYearMoment($gregorianYear);
        if ($dateTime < $newYearMoment) {
            return $gregorianYear + 543 - 1182;
        } else {
            return $gregorianYear + 544 - 1182;
        }
    }

    /**
     * Get animal year from date
     *
     * @param DateTime $dateTime
     * @return int
     */
    public static function getAnimalYear(DateTime $dateTime): int
    {
        $gregorianYear = (int)$dateTime->format('Y');
        $newYearMoment = KhmerDate::getKhNewYearMoment($gregorianYear);
        if ($dateTime < $newYearMoment) {
            return ($gregorianYear + 543 + 4) % 12;
        } else {
            return ($gregorianYear + 544 + 4) % 12;
        }
    }

    /**
     * Get Khmer lunar day information
     *
     * @param int $day Day (0-29)
     * @return array ['count' => int, 'moonStatus' => int]
     */
    public static function getKhmerLunarDay(int $day): array
    {
        return [
            'count' => ($day % 15) + 1,
            'moonStatus' => $day > 14 ? Constants::MOON_STATUS['រោច'] : Constants::MOON_STATUS['កើត']
        ];
    }

    /**
     * Get next month in Khmer calendar
     *
     * Calculates the next month in the Khmer lunar calendar,
     * taking into account leap months (Adhikameas).
     *
     * @param int $khmerMonth Current month index (must be valid month constant)
     * @param int $beYear Buddhist Era year for leap month calculations
     * @return int Next month index
     * @throws InvalidArgumentException If the Khmer month is invalid
     */
    public static function nextMonthOf(int $khmerMonth, int $beYear): int
    {
        if ($beYear < 0) {
            throw new InvalidArgumentException('Buddhist Era year must be positive');
        }

        switch ($khmerMonth) {
            case Constants::LUNAR_MONTHS['មិគសិរ']:
                return Constants::LUNAR_MONTHS['បុស្ស'];
            case Constants::LUNAR_MONTHS['បុស្ស']:
                return Constants::LUNAR_MONTHS['មាឃ'];
            case Constants::LUNAR_MONTHS['មាឃ']:
                return Constants::LUNAR_MONTHS['ផល្គុន'];
            case Constants::LUNAR_MONTHS['ផល្គុន']:
                return Constants::LUNAR_MONTHS['ចេត្រ'];
            case Constants::LUNAR_MONTHS['ចេត្រ']:
                return Constants::LUNAR_MONTHS['ពិសាខ'];
            case Constants::LUNAR_MONTHS['ពិសាខ']:
                return Constants::LUNAR_MONTHS['ជេស្ឋ'];
            case Constants::LUNAR_MONTHS['ជេស្ឋ']:
                if (self::isKhmerLeapMonth($beYear)) {
                    return Constants::LUNAR_MONTHS['បឋមាសាឍ'];
                } else {
                    return Constants::LUNAR_MONTHS['អាសាឍ'];
                }
            case Constants::LUNAR_MONTHS['អាសាឍ']:
                return Constants::LUNAR_MONTHS['ស្រាពណ៍'];
            case Constants::LUNAR_MONTHS['ស្រាពណ៍']:
                return Constants::LUNAR_MONTHS['ភទ្របទ'];
            case Constants::LUNAR_MONTHS['ភទ្របទ']:
                return Constants::LUNAR_MONTHS['អស្សុជ'];
            case Constants::LUNAR_MONTHS['អស្សុជ']:
                return Constants::LUNAR_MONTHS['កត្តិក'];
            case Constants::LUNAR_MONTHS['កត្តិក']:
                return Constants::LUNAR_MONTHS['មិគសិរ'];
            case Constants::LUNAR_MONTHS['បឋមាសាឍ']:
                return Constants::LUNAR_MONTHS['ទុតិយាសាឍ'];
            case Constants::LUNAR_MONTHS['ទុតិយាសាឍ']:
                return Constants::LUNAR_MONTHS['ស្រាពណ៍'];
            default:
                throw new InvalidArgumentException("Invalid Khmer month: $khmerMonth");
        }
    }
}
