<?php

namespace CodeIU\LaravelBladeHeroIcons;

trait HeroIconsTrait
{
    protected static array $heroIconCache = [];

    public function getSvg(string $icon)
    {
        $originStr = $icon;
        $style = config('codeiu-laravel-blade-heroicons.default-style');

        if (preg_match('/(.+)-(o|s)$/', $icon, $m)) {
            $icon = $m[1];

            $tmpStyle = $m[2] ?? null;
            if ($tmpStyle === 'o') {
                $style = 'outline';
            } elseif($tmpStyle === 's') {
                $style = 'solid';
            }
        }

        $cacheKey = 'cache-' . $originStr;
        if (isset(static::$heroIconCache[$cacheKey])) {
            $content = static::$heroIconCache[$cacheKey];
        } else {
            $iconFile = __DIR__ . '/resources/svg/heroicons/' . $style . '/' . $icon . '.svg';
            $content = '';

            if (is_file($iconFile)) {
                $content = file_get_contents($iconFile);

                $content = preg_replace("/[\r\n]/", " ", $content);
                $content = preg_replace("/[\s]{2,}/", " ", $content);
                $content = preg_replace("/>[\s]+(<[a-z\/])/", ">$1", $content);
                $content = trim($content);

                static::$heroIconCache[$cacheKey] = $content;
            }
        }

        return $content;
    }
}
