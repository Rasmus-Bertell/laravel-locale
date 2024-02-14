<?php

namespace Bertell\Locale;

final class Locale
{
    public string $language;

    public string $script;

    public string $region;

    public string $variant;

    public array $extensions = [];

    public string $privateUse;

    public static function parseLocale($locale)
    {
        $instance = new self();
        $locale = strtr($locale, ['_' => '-']);

        $parts = explode('-', $locale);

        for ($i = 0; $i < count($parts); $i++) {
            if ($i === 0 && preg_match('/^[[:alpha:]]{2,8}$/', $parts[$i])) {
                $instance->language = strtolower($parts[$i]);

                continue;
            }

            if ($i < 2 && preg_match('/^[[:alpha:]]{4}$/', $parts[$i])) {
                $instance->script = $parts[$i];

                continue;
            }

            if ($i < 3 && preg_match('/^[[:alpha:]]{2}$/', $parts[$i])) {
                $instance->region = $parts[$i];

                continue;
            }

            if ($i < 4 && preg_match('/^[[:alnum:]]{5,8}$/', $parts[$i])) {
                $instance->variant = $parts[$i];

                continue;
            }

            // Parse extensions and private use subtags
            if (preg_match('/^[[:alnum:]]$/', $parts[$i])) {
                $ext = [$parts[$i]];

                while ($parts[++$i] !== null && !preg_match('/^[[:alnum:]]$/', $parts[$i])) {
                    $ext[] = $parts[$i];
                }

                $instance->extensions[] = implode('-', $ext);

                continue;
            }

            if (strcasecmp($parts[$i], 'x') === 0) {
                $private = [$parts[$i]];

                while ($parts[++$i] !== null && !preg_match('/^[[:alnum:]]$/', $parts[$i])) {
                    $ext[] = $parts[$i];
                }

                continue;
            }
        }

        return $instance;
    }
}
