<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // nastavenie defaultnej dlzky stringu
        // pretoze ak sa string pouzije pri indexovani tak je defaultna dlzka 255 vela
        Schema::defaultStringLength(191);

        //aliasy pre blade componenty pouzivane vo views
        Blade::aliasComponent('components.badge', 'badgealias');
        Blade::aliasComponent('components.updated', 'updated');
        Blade::aliasComponent('components.card', 'card');
        Blade::aliasComponent('components.tags', 'tags');
        Blade::aliasComponent('components.errors', 'errors');
        Blade::aliasComponent('components.comment_form', 'comment_form');
        Blade::aliasComponent('components.comment_list', 'comment_list');

        // jednotne loadovanie dat pre posts.index a posts.show views
        view()->composer(['posts.index','posts.show'], ActivityComposer::class);
    }
}
