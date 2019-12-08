<?php

namespace KGS\Providers;

use Illuminate\Support\ServiceProvider;
use KGS\Document;
use KGS\Observers\DocumentObserver;

class KGSAppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Document::observe(DocumentObserver::class);
    }
}
