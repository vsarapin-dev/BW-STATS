<?php

namespace App\Providers;

use App\Facades\GeneralizedStats\GeneralizedStats;
use Illuminate\Support\ServiceProvider;

class GeneralizedStatsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerAliases();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerServices()
    {
        $this->app->bind(GeneralizedStats::class);

        $this->app->singleton('generalizedStats', function () {
            return new GeneralizedStats;
        });
    }

    /**
     * Register class aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('generalizedStats', GeneralizedStats::class);
    }
}
