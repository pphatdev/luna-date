<?php


namespace PPhatDev\LunarDate;

/**
 * Constants used throughout the Khmer calendar system
 *
 * This class contains all the constant values needed for Khmer calendar calculations,
 * including month names, animal years, era years, and number mappings.
 * Ported from momentkh JavaScript library.
 *
 * @author PPhatDev
 * @package PPhatDev\LunarDate
 * @since 1.0.0
 */
final class Constants
{
    /**
     * Lunar months in Khmer calendar
     * Order: មិគសិរ, បុស្ស, មាឃ, ផល្គុន, ចេត្រ, ពិសាខ, ជេស្ឋ, អាសាឍ, ស្រាពណ៍, ភទ្របទ, អស្សុជ, កត្តិក, បឋមាសាឍ, ទុតិយាសាឍ
     */
    public const LUNAR_MONTHS = ['មិគសិរ' => 0, 'បុស្ស' => 1, 'មាឃ' => 2, 'ផល្គុន' => 3, 'ចេត្រ' => 4, 'ពិសាខ' => 5, 'ជេស្ឋ' => 6, 'អាសាឍ' => 7, 'ស្រាពណ៍' => 8, 'ភទ្របទ' => 9, 'អស្សុជ' => 10, 'កត្តិក' => 11, 'បឋមាសាឍ' => 12, 'ទុតិយាសាឍ' => 13];

    /**
     * Solar months in Khmer calendar
     */
    public const SOLAR_MONTHS = ['មករា' => 0, 'កុម្ភៈ' => 1, 'មីនា' => 2, 'មេសា' => 3, 'ឧសភា' => 4, 'មិថុនា' => 5, 'កក្កដា' => 6, 'សីហា' => 7, 'កញ្ញា' => 8, 'តុលា' => 9, 'វិច្ឆិកា' => 10, 'ធ្នូ' => 11];

    /**
     * Animal years in the 12-year cycle
     */
    public const ANIMAL_YEARS = ['ជូត', 'ឆ្លូវ', 'ខាល', 'ថោះ', 'រោង', 'ម្សាញ់', 'មមី', 'មមែ', 'វក', 'រកា', 'ច', 'កុរ'];

    /**
     * Era years in the 10-year cycle
     */
    public const ERA_YEARS = ['សំរឹទ្ធិស័ក', 'ឯកស័ក', 'ទោស័ក', 'ត្រីស័ក', 'ចត្វាស័ក', 'បញ្ចស័ក', 'ឆស័ក', 'សប្តស័ក', 'អដ្ឋស័ក', 'នព្វស័ក'];

    /**
     * Moon status (waxing or waning)
     */
    public const MOON_STATUS = ['កើត' => 0, /*Waxing moon*/ 'រោច' => 1 /*Waning moon*/];

    /**
     * Moon status short form
     */
    public const MOON_STATUS_SHORT = ['ក', 'រ'];

    /**
     * Weekdays in Khmer
     */
    public const WEEKDAYS = ['អាទិត្យ', 'ចន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];

    /**
     * Weekdays short form
     */
    public const WEEKDAYS_SHORT = ['អា', 'ច', 'អ', 'ព', 'ព្រ', 'សុ', 'ស'];

    /**
     * Month names for solar calendar
     */
    public const MONTHS = ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'];

    /**
     * Cached New Year moments for specific years where calculation differs
     */
    public const KH_NEW_YEAR_MOMENTS = [
        '1879' => '12-04-1879 11:36',
        '1897' => '13-04-1897 02:00',
        '2011' => '14-04-2011 13:12',
        '2012' => '14-04-2012 19:11',
        '2013' => '14-04-2013 02:12',
        '2014' => '14-04-2014 08:07',
        '2015' => '14-04-2015 14:02',
    ];

    /**
     * Khmer number symbols
     */
    public const KHMER_NUMBERS = ['0' => '០', '1' => '១', '2' => '២', '3' => '៣', '4' => '៤', '5' => '៥', '6' => '៦', '7' => '៧', '8' => '៨', '9' => '៩'];

    /**
     * Reverse mapping for Khmer numbers to Arabic
     */
    public const ARABIC_NUMBERS = ['០' => '0', '១' => '1', '២' => '2', '៣' => '3', '៤' => '4', '៥' => '5', '៦' => '6', '៧' => '7', '៨' => '8', '៩' => '9'];
}
