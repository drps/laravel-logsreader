<?php

namespace App\Providers;

use App\Console\Commands\CachePaginator;
use App\Http\Controllers\LogsController;
use App\Service\SearchService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LogsController::class, function (Application $app) {
            $config = $app->make('config')->get('app');
            return new LogsController($app->get(SearchService::class), $config['perpage']);
        });

        $this->app->singleton(CachePaginator::class, function (Application $app) {
            $config = $app->make('config')->get('app');
            return new CachePaginator($config['perpage']);
        });
    }
}
