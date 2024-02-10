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
        $instance = new static();
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

            if (preg_match('/^[A-WY-Za-wy-z0-9]$/', $parts[$i])) {
                $instance->extensions[] = implode('-', [$parts[$i], $parts[++$i]]);

                continue;
            }

            // Append multipart extensions
            if (preg_match('/^[[:alnum:]]{2,}$/', $parts[$i])) {
                $instance->extensions[array_key_last($instance->extensions)] =
                implode('-', [
                    $instance->extensions[array_key_last($instance->extensions)],
                    $parts[$i]
                ]);

                continue;
            }

            if (
                true === empty($instance->privateUse)
                && preg_match('/^[xX]$/', $parts[$i])
            ) {
                $instance->privateUse = implode('-', [$parts[$i], $parts[++$i]]);
            }
        }

        return $instance;
    }
}
