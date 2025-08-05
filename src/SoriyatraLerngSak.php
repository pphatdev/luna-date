<?php

namespace PPhatDev\LunaDate;

/**
 * Soriyatra Lerng Sak calculations for Khmer New Year
 * Ported from getSoriyatraLerngSak.js in momentkh
 */
class SoriyatraLerngSak
{
    /**
     * Calculate Soriyatra Lerng Sak information for a given Jolak Sakaraj year
     *
     * @param int $jsYear Jolak Sakaraj year
     * @return array
     */
    public static function calculate(int $jsYear): array
    {
        $info = self::getInfo($jsYear);

        $has366day = self::getHas366day($jsYear);
        $isAthikameas = self::getIsAthikameas($jsYear);
        $isChantreathimeas = self::getIsChantreathimeas($jsYear);
        $jesthHas30 = $isChantreathimeas;

        // Calculate day of Lerng Sak
        $dayLerngSak = ($info['harkun'] - 2) % 7;

        // Calculate lunar date of Lerng Sak
        $bodithey = $info['bodithey'];
        if (self::getIsAthikameas($jsYear - 1) && self::getIsChantreathimeas($jsYear - 1)) {
            $bodithey = ($bodithey + 1) % 30;
        }

        $lunarDateLerngSak = [
            'day' => $bodithey >= 6 ? $bodithey - 1 : $bodithey,
            'month' => $bodithey >= 6 ? Constants::LUNAR_MONTHS['ចេត្រ'] : Constants::LUNAR_MONTHS['ពិសាខ']
        ];

        // Calculate Sotins for New Year days
        $sotins = $has366day ? [363, 364, 365, 366] : [362, 363, 364, 365];
        $newYearsDaySotins = [];

        foreach ($sotins as $sotin) {
            $sunInfo = self::getSunInfo($sotin);
            $newYearsDaySotins[] = [
                'sotin' => $sotin,
                'angsar' => $sunInfo['angsar'],
                'avaman' => $sunInfo['avaman']
            ];
        }

        // Calculate time of New Year
        $timeOfNewYear = self::calculateNewYearTime($newYearsDaySotins);

        return [
            'harkun' => $info['harkun'],
            'kromathopol' => $info['kromathopol'],
            'avaman' => $info['avaman'],
            'bodithey' => $info['bodithey'],
            'has366day' => $has366day,
            'isAthikameas' => $isAthikameas,
            'isChantreathimeas' => $isChantreathimeas,
            'jesthHas30' => $jesthHas30,
            'dayLerngSak' => $dayLerngSak,
            'lunarDateLerngSak' => $lunarDateLerngSak,
            'newYearsDaySotins' => $newYearsDaySotins,
            'timeOfNewYear' => $timeOfNewYear
        ];
    }

    /**
     * Calculate basic info (harkun, kromathopol, avaman, bodithey)
     *
     * @param int $jsYear
     * @return array
     */
    protected static function getInfo(int $jsYear): array
    {
        $h = 292207 * $jsYear + 373;
        $harkun = intval($h / 800) + 1;
        $kromathopol = 800 - ($h % 800);

        $a = 11 * $harkun + 650;
        $avaman = $a % 692;
        $bodithey = ($harkun + intval($a / 692)) % 30;

        return [
            'harkun' => $harkun,
            'kromathopol' => $kromathopol,
            'avaman' => $avaman,
            'bodithey' => $bodithey
        ];
    }

    /**
     * Check if year has 366 days
     *
     * @param int $jsYear
     * @return bool
     */
    protected static function getHas366day(int $jsYear): bool
    {
        $info = self::getInfo($jsYear);
        return $info['kromathopol'] <= 207;
    }

    /**
     * Check if year is Athikameas (leap month year)
     *
     * @param int $jsYear
     * @return bool
     */
    protected static function getIsAthikameas(int $jsYear): bool
    {
        $info = self::getInfo($jsYear);
        $bodithey = $info['bodithey'];

        if ($bodithey >= 25 || $bodithey <= 6) {
            if ($bodithey === 25) {
                $nextInfo = self::getInfo($jsYear + 1);
                return $nextInfo['bodithey'] !== 5;
            }
            if ($bodithey === 24) {
                $nextInfo = self::getInfo($jsYear + 1);
                return $nextInfo['bodithey'] === 6;
            }
            return true;
        }
        return false;
    }

    /**
     * Check if year is Chantreathimeas (leap day year)
     *
     * @param int $jsYear
     * @return bool
     */
    protected static function getIsChantreathimeas(int $jsYear): bool
    {
        $infoOfYear = self::getInfo($jsYear);
        $infoOfNextYear = self::getInfo($jsYear + 1);
        $infoOfPreviousYear = self::getInfo($jsYear - 1);
        $has366day = self::getHas366day($jsYear);

        return (($has366day && $infoOfYear['avaman'] < 127) ||
            (!($infoOfYear['avaman'] === 137 && $infoOfNextYear['avaman'] === 0) &&
                ((!$has366day && $infoOfYear['avaman'] < 138) ||
                    ($infoOfPreviousYear['avaman'] === 137 && $infoOfYear['avaman'] === 0)
                )
            ));
    }

    /**
     * Get sun information for a given sotin
     *
     * @param int $sotin
     * @return array
     */
    protected static function getSunInfo(int $sotin): array
    {
        // Simplified sun calculation - this would need more complex astronomical calculations
        // For now, using basic approximation
        $angsar = $sotin % 2; // Simplified
        $avaman = ($sotin * 17) % 692; // Simplified

        return [
            'angsar' => $angsar,
            'avaman' => $avaman
        ];
    }

    /**
     * Calculate New Year time from sotins
     *
     * @param array $newYearsDaySotins
     * @return array
     */
    protected static function calculateNewYearTime(array $newYearsDaySotins): array
    {
        // Simplified time calculation
        // In a full implementation, this would involve complex astronomical calculations
        $hour = 6 + ($newYearsDaySotins[0]['avaman'] % 24);
        $minute = ($newYearsDaySotins[0]['avaman'] % 60);

        return [
            'hour' => $hour,
            'minute' => $minute
        ];
    }
}
