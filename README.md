# Laravel Blade HeroIcons Directives

## install

```
composer require codeiucom/laravel-blade-heroicons
```

## use directive

ex) @heroicons-`iconName`-`style`("`classes`")

1. default
    ```
    @heroicons-folder("w-6 h-6")
    ```
1. outline
    ```
    @heroicons-folder-o("w-6 h-6")
    ```
1. solid
    ```
    @heroicons-folder-s("w-6 h-6")
    ```

## use component

1. default
    ```html
    <heroicons-folder class="w-6 h-6"/>
    <!-- or -->
    <heroicons-folder class="w-6 h-6"></heroicons-folder>
    ```
1. outline
    ```html
    <heroicons-folder-o class="w-6 h-6"/>
    <!-- or -->
    <heroicons-folder-o class="w-6 h-6"></heroicons-folder-o>
    ```
1. solid
    ```html
    <heroicons-folder-s class="w-6 h-6"/>
    <!-- or -->
    <heroicons-folder-s class="w-6 h-6"></heroicons-folder-s>
    ```

## options

1. change directive (default: heroicons)  
   ex)
   ```dotenv
      CODEIU_LARAVEL_BLADE_HEROICONS_PREFIX=icons
   ```
2. change default style (default: solid)  
   ex)
   ```dotenv
      CODEIU_LARAVEL_BLADE_HEROICONS_DEFAULT_STYLE=outline
   ```
3. set default class (default: empty)  
   ex)
   ```dotenv
      CODEIU_LARAVEL_BLADE_HEROICONS_DEFAULT_CLASSES="w-6 h-6"
   ```
   if you use default class with tailwindcss, add below in tailwind.config.js
   ```
   module.exports = {
      ...
      content: [
         './storage/framework/views/*.php',
      ],
      ...
   }
   ```