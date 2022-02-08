<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Schema;
use App\Models\BlogPost;
use App\Observers\BlogPostObserver;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Services\Counter;
use App\Services\DummyCounter;

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

        // registrovanie observerov
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        //  registrovanie service (containeru)
        // $app bude pristupna Counteru
        // injektujeme dependencies
        $this->app->singleton(Counter::class, function ($app) {
            return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                env('COUNTER_TIMEOUT')
            );
        });

        // registrujeme contract a urcime ktoru triedu service ma pouzivat
        $this->app->bind(
            'App\Contracts\CounterContract',
            Counter::class
            // takto staci vymenit service
            //DummyCounter ::class
        );
    }
}
