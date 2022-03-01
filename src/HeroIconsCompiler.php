<?php

namespace CodeIU\LaravelBladeHeroIcons;

use Illuminate\View\Compilers\ComponentTagCompiler;

class HeroIconsCompiler extends ComponentTagCompiler
{
    use HeroIconsTrait;

    protected string $directive = 'heroicons';
    protected array $defaultClass = [];

    public function compile(string $value)
    {
        $this->directive = config('codeiu-laravel-blade-heroicons.prefix');
        $tmpClasses = config('codeiu-laravel-blade-heroicons.default-classes');
        if (!empty($tmpClasses)) {
            $tmpArr = explode(' ', $tmpClasses);
            foreach ($tmpArr as $val) {
                $tmpKey = preg_replace('/-[0-9.\/]+$/', '-', $val);
                $this->defaultClass[$tmpKey] = $val;
            }
        }

        return $this->compileTags($value);
    }

    public function compileTags(string $value)
    {
        $value = $this->compileSelfClosingTags($value);
        $value = $this->compileOpeningTags($value);
        $value = $this->compileClosingTags($value);

        $value = $this->compileStatements($value);

        return $value;
    }

    protected function compileSelfClosingTags(string $value)
    {
        $pattern = "/
            <
                \s*
                {$this->directive}[-\:]([\w\-\:\.]*)
                \s*
                (?<attributes>
                    (?:
                        \s+
                        (?:
                            (?:
                                \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                            )
                            |
                            (?:
                                [\w\-:.@]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \'[^\']*\'
                                        |
                                        [^\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                    \s*
                )
            \/>
        /x";

        return preg_replace_callback($pattern, function (array $matches) {
            $this->boundAttributes = [];

            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            $svg = $this->componentString($matches[1], $attributes);
            if (empty($svg)) {
                return e($matches[0]);
            }

            return $svg;
        }, $value);
    }

    /**
     * Compile the opening tags within the given string.
     *
     * @param  string  $value
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function compileOpeningTags(string $value)
    {
        $pattern = "/
            <
                \s*
                {$this->directive}[-\:]([\w\-\:\.]*)
                (?<attributes>
                    (?:
                        \s+
                        (?:
                            (?:
                                \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                            )
                            |
                            (?:
                                [\w\-:.@]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \'[^\']*\'
                                        |
                                        [^\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                    \s*
                )
                (?<![\/=\-])
            >
        /x";

        return preg_replace_callback($pattern, function (array $matches) {
            $this->boundAttributes = [];

            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            $svg = $this->componentString($matches[1], $attributes);
            if (empty($svg)) {
                return e($matches[0]);
            }

            return $svg;
        }, $value);
    }

    protected function compileClosingTags(string $value)
    {
        return preg_replace_callback("/<\/\s*{$this->directive}-([\w\-\:\.]+)\s*>/", function (array $matches) {
            if (!empty($matches[1]) && !empty(static::$heroIconCache['cache-' . $matches[1]])) {
                return '';
            }

            return e($matches[0]);
        }, $value);
    }

    protected function componentString(string $icon, array $attributes)
    {
        $svg = $this->getSvg($icon);

        if (empty($svg)) {
            return null;
        }

        $tmpClass = $attributes['class'] ?? '';
        $tmpClass = trim($tmpClass, '"\'');
        if (!empty($this->defaultClass)) {
            foreach ($this->defaultClass as $key => $val) {
                if (!preg_match('/\b' . $key . '/', $tmpClass)) {
                    $tmpClass .= ' ' . $val;
                }
            }
        }
        $attributes['class'] = $tmpClass;

        $attr = [];
        foreach ($attributes as $attribute => $value) {
            $value = trim($value, '"\'');
            $attr[] = $attribute . '="' . $value . '"';
        }
        $attr = implode(' ', $attr);

        if (!empty($attr)) {
            $svg = str_replace('<svg xmlns=', '<svg ' . $attr . ' xmlns=', $svg);
        }

        $svg .= '<?php /* heroicons: ' . $icon . ' */?>';

        return $svg;
    }


    protected function compileStatements($value)
    {
        return preg_replace_callback(
            '/\B@(@?' . $this->directive . '-[a-z-]+)(\( ( (?>[^()]+) | (?2) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $value
        );
    }

    protected function compileStatement($match)
    {
        if (str_contains($match[1], '@')) {
            $match[0] = isset($match[2]) ? $match[1] . $match[2] : $match[1];
        } else {
            $icon = $match[1] ?? '';
            $icon = preg_replace('/^' . $this->directive . '-/', '', $icon);

            $class = $match[3] ?? '';
            $class = trim($class, '"\'');
            if (empty($class)) {
                $attr = [];
            } else {
                $attr = ['class' => $class];
            }

            $svg = $this->componentString($icon, $attr);

            if (!empty($svg)) {
                return $svg;
            }
        }

        return isset($match[2]) ? $match[0] : $match[0] . $match[1];
    }
}
