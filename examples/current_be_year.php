<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PPhatDev\LunarDate\KhmerDate;

// ANSI color codes
$colors = [
    'yellow' => "\033[33m",
    'green'  => "\033[32m",
    'cyan'   => "\033[36m",
    'magenta'=> "\033[35m",
    'reset'  => "\033[0m"
];

echo "{$colors['yellow']}=== Running Current BE Year Calculation ==={$colors['reset']}\n";
$khmerDate = new KhmerDate();

echo "{$colors['green']}Current BE Year: {$khmerDate->khYear()}{$colors['reset']} \n";
echo "{$colors['cyan']}Current Gregorian Year: {$khmerDate->format('Y')}{$colors['reset']} \n";
echo "{$colors['magenta']}Current Khmer Month: {$khmerDate->khMonth()}{$colors['reset']} \n";
echo "{$colors['yellow']}Current Khmer Day: {$khmerDate->khDay()}{$colors['reset']} \n";
echo "{$colors['green']}Current Khmer Date: {$khmerDate->format('d-m-Y')}{$colors['reset']} \n";