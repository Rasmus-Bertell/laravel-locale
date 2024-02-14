<?php

$locales = [
    'de',
    'fr',
    'ja',
    'zh-Hant',
    'zh-Hans',
    'sr-Cyrl',
    'sr-Latn',
    'zh-Hans-CN',
    'sr-Latn-CS',
    'sl-rozaj',
    'sl-nedis',
    'de-CH-1901',
    'sl-IT-nedis',
    'sl-Latn-IT-nedis',
    'de-DE',
    'en-US',
    'es-419',
    'de-CH-x-phonebk',
    'az-Arab-x-AZE-derbend',
    'zh-min',
    'zh-min-nan-Hant-CN',
    'qaa-Qaaa-QM-x-southern',
    'de-Qaaa',
    'sr-Latn-QM',
    'sr-Qaaa-CS',
    'en-US-u-islamCal',
    'zh-CN-a-myExt-x-private',
    'en-a-myExt-b-another',

    // Invalid tags
    'de-419-DE',
    'a-DE',
    'ar-a-aaa-b-bbb-a-ccc',
];

foreach ($locales as $locale) {
    var_dump($locale, Bertell\Locale\Locale::parseLocale($locale));
}

it('can parse a locale', function () {
    $locale = Bertell\Locale\Locale::parseLocale('englishasd');
    expect($locale)->toBe('engl');
});
