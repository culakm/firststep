<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function ($user) {
                return $user->is_admin;
            }
        );

        Gate::define('mailable', function ($user) {
                return $user->is_admin;
            }
        );
        

        // Registrujeme Gate pre user-blogpost
        // Gate::define('update-post', 
        //     function ($user, $post) {
        //         return $user->id === $post->user_id;
        //     }
        // );
        // V kontroleri
        // Gate::allows('update-post', $post);
        // alebo
        // $this->authorize('update-post', $post);

        // Definujeme jednotlivo Gate
        // Gate::define('posts.update','App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete','App\Policies\BlogPostPolicy@delete');

        // Definujeme Gates naraz (posts.create,posts.view, posts.update a posts.delete)
        Gate::resource('posts', 'App\Policies\BlogPostPolicy');

        // Preskakujeme ostatne gate pre admina, admin moze u vsetkych route update a delete 
        Gate::before( 
            function ($user, $ability) {
                if ($user->is_admin && in_array($ability,['update','delete'])){
                    return true;
                }
            }
        );


        // 
        // Gate::after( 
        //     function ($user, $ability, $result) {
        //         if ($user->is_admin) {
        //             return true;
        //         }
        //     }
        // );

    }
}
