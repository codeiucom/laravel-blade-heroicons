<?php

$tmpClass = trim(env('CODEIU_LARAVEL_BLADE_HEROICONS_DEFAULT_CLASSES', ''));
$tmpClass = preg_replace('/[\s]{2,}/', ' ', $tmpClass);

return [
    'prefix' => env('CODEIU_LARAVEL_BLADE_HEROICONS_PREFIX', 'heroicons'),
    'default-style' => env('CODEIU_LARAVEL_BLADE_HEROICONS_DEFAULT_STYLE', 'solid'),
    'default-classes' => $tmpClass,
];
