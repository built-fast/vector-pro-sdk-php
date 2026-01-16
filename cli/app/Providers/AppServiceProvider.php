<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Config\ConfigRepository;
use App\Services\Config\XdgPathResolver;
use App\Services\Output\OutputFormatter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(XdgPathResolver::class);

        $this->app->singleton(ConfigRepository::class, function ($app): ConfigRepository {
            return new ConfigRepository($app->make(XdgPathResolver::class));
        });

        $this->app->singleton(OutputFormatter::class);
    }
}
