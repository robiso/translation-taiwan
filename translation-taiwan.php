<?php

global $Wcms;

$Wcms->addListener('settings', function ($args) {
    $translations = array_map(static function ($line) {
        return str_getcsv($line, ',', '"', '\\'); // the defaults, spelled out: PHP 8.4 deprecates relying on the escape default
    }, file(__DIR__ . '/zh-tw.csv'));

    foreach ($translations as $replacement) {
        if (count($replacement) !== 2) {
            continue;
        }

        // Non-ASCII characters go out as numeric entities, so the label renders correctly even on a host that sends a non-UTF-8 charset header
        $args[0] = preg_replace("/{$replacement[0]}/", mb_encode_numericentity($replacement[1], [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8'), $args[0]);
    }

    return $args;
});
