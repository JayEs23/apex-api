<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        Route::group(['prefix' => 'api'], function () {
            Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
        });

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
