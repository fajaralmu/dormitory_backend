<?php

namespace App\Providers;

use App\Services\AccountService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Socialite\LaravelPassportProvider;

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
        $accountService = new AccountService();
        $this->app->bind(AccountService::class, function ($app) use ($accountService) {
            return $accountService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.card', 'card');
        Blade::component('components.modal', 'modal');

        $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
        $socialite->extend(
            'portalsekolah',
            function ($app) use ($socialite) {
                $config = config('services.portalsekolah');
                $config['guzzle'] = ['verify' => false];

                return $socialite->buildProvider(LaravelPassportProvider::class, $config);
            }
        );
    }
}
