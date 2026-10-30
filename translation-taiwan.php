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

// Alerts reach the hook as [message] (WonderCMS 3.4.2 and newer; older cores never call it)
$Wcms->addListener('alert', function ($args) {
    if (!is_array($args) || !isset($args[0]) || !is_string($args[0])) {
        return $args;
    }

    $translations = array_map(static function ($line) {
        return str_getcsv($line, ',', '"', '\\');
    }, file(__DIR__ . '/zh-tw.csv'));

    $tags = static function ($html) {
        preg_match_all('/<[^>]*>/', $html, $matches);
        return $matches[0];
    };
    $message = $args[0];
    $structure = $tags($message);

    foreach ($translations as $replacement) {
        if (count($replacement) !== 2) {
            continue;
        }

        // Only text changes: a row that would add, drop or alter a tag (a link, its target tab) is skipped
        $translated = preg_replace("/{$replacement[0]}/", mb_encode_numericentity($replacement[1], [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8'), $message);
        if (is_string($translated) && $tags($translated) === $structure) {
            $message = $translated;
        }
    }

    // A core before 3.7.0 skips an alert that is already queued by comparing the English text, which a translated copy never equals; there the earlier copy goes, so an alert raised twice in one request (a full disk fails every save) still shows once. 3.7.0 and newer compare the translated text themselves, so the queue is left alone
    if ($message !== $args[0] && defined('VERSION') && version_compare(VERSION, '3.7.0', '<') && isset($_SESSION['alert']) && is_array($_SESSION['alert'])) {
        foreach ($_SESSION['alert'] as $class => $queued) {
            foreach ((array)$queued as $i => $alert) {
                if (is_array($alert) && isset($alert['message']) && $alert['message'] === $message) {
                    unset($_SESSION['alert'][$class][$i]);
                }
            }
        }
    }

    $args[0] = $message;
    return $args;
});
