<?php

namespace CodeIU\LaravelBladeHeroIcons;

use CodeIU\LaravelBladeHeroIcons\HeroIconsCompiler;
use Illuminate\Support\ServiceProvider;

class HeroIconServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/codeiu-laravel-blade-heroicons.php', 'codeiu-laravel-blade-heroicons'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/codeiu-laravel-blade-heroicons.php' => config_path('codeiu-laravel-blade-heroicons.php'),
            ], 'codeiu-laravel-blade-heroicons-config');
        }

        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(HeroIconsCompiler::class)->compile($string);
            });
        }
    }
}
