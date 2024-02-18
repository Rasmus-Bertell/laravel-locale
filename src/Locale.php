<?php

namespace Bertell\Locale;

use InvalidArgumentException;

final class Locale
{
    public ?string $language = null;

    public ?string $script = null;

    public ?string $region = null;

    public ?string $variant = null;

    public array $extensions = [];

    public ?string $privateUse = null;

    public static function isValid($locale): bool
    {
        try {
            self::parseLocale($locale);
        } catch (InvalidArgumentException) {
            return false;
        }

        return true;
    }

    public static function parseLocale($locale)
    {
        $instance = new self();
        $locale = strtr($locale, ['_' => '-']);

        $parts = explode('-', $locale);

        for ($i = 0; $i < count($parts); $i++) {
            if ($i === 0 && preg_match('/^[[:alpha:]]{2,8}$/', $parts[$i])) {
                if (empty($instance->language) === false) {
                    throw new InvalidArgumentException('Duplicate language tag found');
                }

                $instance->language = strtolower($parts[$i]);

                if (strlen($parts[$i]) < 4) {
                    for ($i++; $i < 4 && $i < count($parts); $i++) {
                        if (preg_match('/^[[:alpha:]]{3}$/', $parts[$i])) {
                            $instance->language = implode('-', [$instance->language, $parts[$i]]);
                        } else {
                            $i--;
                            break;
                        }
                    }
                }

                continue;
            } elseif ($i === 0) {
                throw new InvalidArgumentException("Invalid language tag: {$locale}");
            }

            if (preg_match('/^[[:alpha:]]{4}$/', $parts[$i])) {
                if (empty($instance->script) === false) {
                    throw new InvalidArgumentException('Duplicate script tag found');
                }

                $instance->script = $parts[$i];

                continue;
            }

            if (preg_match('/^(?:[[:alpha:]]{2}|[[:digit:]]{3})$/', $parts[$i])) {
                if (empty($instance->region) === false) {
                    throw new InvalidArgumentException('Duplicate region tag found');
                }

                $instance->region = $parts[$i];

                continue;
            }

            if (preg_match('/^(?:(?:[[:alnum:]]{5,8})|(?:[[:digit:]][[:alnum:]]{3}))$/', $parts[$i])) {
                if (empty($instance->variant) === false) {
                    throw new InvalidArgumentException('Duplicate variant tag found');
                }

                $instance->variant = $parts[$i];

                continue;
            }

            if (preg_match('/^[A-WYZa-wyz[:digit:]]$/', $parts[$i])) {
                $key = $parts[$i];
                $ext = [];

                if (in_array($key, array_keys($instance->extensions), true) === true) {
                    throw new InvalidArgumentException('Duplicate extension found');
                }

                while (empty($parts[++$i]) === false && ! preg_match('/^[[:alnum:]]$/', $parts[$i])) {
                    $ext[] = $parts[$i];
                }

                $i--;

                $instance->extensions[$key] = implode('-', $ext);

                continue;
            }

            if (strcasecmp($parts[$i], 'x') === 0) {
                if (empty($instance->privateUse) === false) {
                    throw new InvalidArgumentException('Duplicate private use tag found');
                }

                $private = [$parts[$i]];

                while (empty($parts[++$i]) === false) {
                    $private[] = $parts[$i];
                }

                $instance->privateUse = implode('-', $private);

                continue;
            }
        }

        return $instance;
    }
}
