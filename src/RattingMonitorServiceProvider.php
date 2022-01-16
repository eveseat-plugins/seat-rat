<?php

namespace RecursiveTree\Seat\RattingMonitor;

use Seat\Services\AbstractSeatPlugin;

use  Seat\Eveapi\Jobs\Status\Status;

class RattingMonitorServiceProvider extends AbstractSeatPlugin
{
    public function boot(){
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang/', 'rattingmonitor');
        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'rattingmonitor');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->publishes([
            __DIR__ . '/resources/js' => public_path('rattingmonitor/js')
        ]);
    }

    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/Config/rattingmonitor.sidebar.php','package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/rattingmonitor.permissions.php', 'rattingmonitor');
    }

    public function getName(): string
    {
        return 'SeAT Ratting Monitor';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-rat';
    }

    public function getPackagistVendorName(): string
    {
        return 'recursivetree';
    }
}